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
        $query = Post::withCount('favorites'); // ← お気に入り数を一緒に取得
        
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // 並び替え処理
        $sort = $request->input('sort');
        if ($sort === 'old') {
            $query->orderBy('created_at', 'asc'); // 古い投稿順
        } elseif ($sort === 'favorites') {
            $query->orderBy('favorites_count', 'desc'); // 人気順
        } else {
            $query->orderBy('created_at', 'desc'); // デフォルト：新しい投稿順
        }

        $posts = $query->paginate(1);//ページネーション
        return view('posts.index', compact('posts', 'search', 'sort'));
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
        $post = Post::with('user')->findOrFail($id);
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
            $posts = $user->favoritePosts()->with('user')->orderBy('created_at', 'desc')->paginate(1);// お気に入り投稿
        } else {
            $posts = $user->posts()->with('favorites')->orderBy('created_at', 'desc')->paginate(1);// 自分の投稿
        }

        return view('posts.mypage', compact('posts', 'tab'));
    }
}
