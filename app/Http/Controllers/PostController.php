<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Favorite;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)//!データの一覧表示 index
    {
        $query = Post::query();
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%");
        }
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()//!新規作成用フォームの表示 create
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//!データの新規保存 store
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'body' => 'nullable',
            'program_path' => 'required',
            'arguments' => 'nullable',
            'run_datetime' => 'required|date',
            'screenshot' => 'nullable|image|max:2048',
        ]);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        }

        Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'program_path' => $validated['program_path'],
            'arguments' => $validated['arguments'] ?? null,
            'run_datetime' => $validated['run_datetime'],
            'enabled' => true,
            'screenshot_path' => $screenshotPath, // ← DBに保存される
        ]);

        return redirect()->route('posts.index')->with('success', 'タスクを登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)//!データの個別表示 show
    {
        $post = \App\Models\Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)//!データの編集用フォームの表示 edit
    {
        $post = \App\Models\Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)//!データの更新 update
    {
        $validated = $request->validate([
            'title' => 'required|max:255|unique:posts,title,'.$id,
            'body' => 'nullable',
            'program_path' => 'required',
            'arguments' => 'nullable',
            'run_datetime' => 'required|date',
            'screenshot' => 'nullable|image|max:2048',
        ]);

        $post = \App\Models\Post::findOrFail($id);

        // スクショ更新
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
            $post->screenshot_path = $screenshotPath;
        }

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'] ?? null,
            'program_path' => $validated['program_path'],
            'arguments' => $validated['arguments'] ?? null,
            'run_datetime' => $validated['run_datetime'],
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'タスクを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)//!データの削除 destroy
    {
        $post = \App\Models\Post::findOrFail($id);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'タスクを削除しました');
    }

    public function mypage(Request $request)
    {
        $tab = $request->get('tab', 'history'); // デフォルトは history
        $user = auth()->user();

        if ($tab === 'like') {
            $posts = $user->favoritePosts()->with('user')->get();
        } else {
            $posts = $user->posts()->with('favorites')->get();
        }

        return view('posts.mypage', compact('posts', 'tab'));
    }
}
