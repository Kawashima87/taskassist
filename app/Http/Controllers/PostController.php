<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Favorite;
use Illuminate\Support\Facades\Log;

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

        $posts = $query->paginate(6);//ページネーション
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
        'run_datetime' => 'required|date',
        'screenshot' => 'nullable|image|max:2048',
        'action_type' => 'required|in:program,popup',
        'program_path' => 'nullable|required_if:action_type,program',
        'arguments' => 'nullable',
        'popup_title' => 'nullable|required_if:action_type,popup',
        'popup_message' => 'nullable|required_if:action_type,popup',
    ]);

    $screenshotPath = null;
    if ($request->hasFile('screenshot')) {
        $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
    }

    // ★ DB保存
    $post = Post::create([
        'user_id' => auth()->id(),
        'title' => $validated['title'],
        'body' => $validated['body'] ?? null,
        'run_datetime' => $validated['run_datetime'],
        'action_type' => $validated['action_type'],
        'program_path' => $validated['program_path'] ?? null,
        'arguments' => $validated['arguments'] ?? null,
        'popup_title' => $validated['popup_title'] ?? null,
        'popup_message' => $validated['popup_message'] ?? null,
        'enabled' => true,
        'screenshot_path' => $screenshotPath,
    ]);

    // ★ タスクスケジューラ登録
    $datetime = \Carbon\Carbon::parse($post->run_datetime)->format('Y-m-d H:i');

    if ($post->action_type === 'program') {
        // アプリ実行
        $program = $post->program_path;
        $args = $post->arguments ?? '';

        $command = 'powershell -Command "'
            . '$action = New-ScheduledTaskAction -Execute \'' . $program . '\' '
            . ($args !== '' ? ' -Argument \'' . $args . '\'' : '') . '; '
            . '$trigger = New-ScheduledTaskTrigger -Once -At \'' . $datetime . '\'; '
            . 'Register-ScheduledTask -TaskName \'' . $post->title . '\''
            . ' -Description \'' . ($post->body ?? '') . '\''
            . ' -Action $action -Trigger $trigger -Force"';

    } elseif ($post->action_type === 'popup') {
        // ★ PowerShell ファイルを書き出す処理
        $filename = 'popup_' . uniqid() . '.ps1';  // ファイル名（ユニーク化）
        $filePath = storage_path('app/' . $filename);  // 保存先パス
        $popupTitle   = $post->popup_title ?? '通知';
        $popupMessage = $post->popup_message ?? '時間になりました！';

        // PowerShell の中身（CP932で保存 → 日本語OK）
        $script = "(New-Object -ComObject Wscript.Shell).Popup('$popupMessage',0,'$popupTitle',64)";
        if (file_put_contents($filePath, mb_convert_encoding($script, 'CP932', 'UTF-8')) === false) {
            \Log::error("ps1ファイルの書き込み失敗", ['filePath' => $filePath]);
        }

        // ★ ps1_path を保存
        $post->ps1_path = $filePath;
        $post->save();

        // ★ タスクスケジューラ登録
        $command = 'powershell -Command "'
            . '$action = New-ScheduledTaskAction -Execute \'%SystemRoot%\\System32\\WindowsPowerShell\\v1.0\\powershell.exe\' '
            . '-Argument \'-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File \"' . $filePath . '\"\' ; '
            . '$trigger = New-ScheduledTaskTrigger -Once -At \'' . $datetime . '\'; '
            . 'Register-ScheduledTask -TaskName \'' . $post->title . '\''
            . ' -Description \'' . ($post->body ?? '') . '\''
            . ' -Action $action -Trigger $trigger -Force"';
    }

    exec($command, $output, $result);

    if ($result !== 0) {
        \Log::error("Register-ScheduledTask failed (store)", [
            'command' => $command,
            'output' => $output,
        ]);
        return redirect()->back()->withErrors(['task_error' => 'タスク登録に失敗しました']);
    }

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
        'program_path' => 'nullable|required_if:action_type,program',
        'arguments' => 'nullable',
        'run_datetime' => 'required|date',
        'screenshot' => 'nullable|image|max:2048',
        'action_type' => 'required|in:program,popup',
        'popup_title' => 'nullable|required_if:action_type,popup',
        'popup_message' => 'nullable|required_if:action_type,popup',
    ]);

    $post = Post::findOrFail($id);
    $oldTitle = $post->title;
    $oldPs1   = $post->ps1_path;   // ← 旧ファイルのパスを保持

    // スクショ更新
    if ($request->hasFile('screenshot')) {
        $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        $post->screenshot_path = $screenshotPath;
    }

    // DB更新
    $post->fill([
        'title' => $validated['title'],
        'body' => $validated['body'] ?? null,
        'program_path' => $validated['program_path'] ?? null,
        'arguments' => $validated['arguments'] ?? null,
        'run_datetime' => $validated['run_datetime'],
        'action_type' => $validated['action_type'],
        'popup_title' => $validated['popup_title'] ?? null,
        'popup_message' => $validated['popup_message'] ?? null,
    ]);
    $post->save();

    // 古いタスク削除（タイトル変更時のみ）
    if ($oldTitle !== $post->title) {
        $deleteCommand = 'powershell -Command "Unregister-ScheduledTask -TaskName ' . escapeshellarg($oldTitle) . ' -Confirm:$false"';
        exec($deleteCommand);
    }

    // 古い ps1 ファイル削除（popup の場合）
    if ($oldPs1 && file_exists($oldPs1)) {
        if (!unlink($oldPs1)) {
            \Log::warning("ps1ファイル削除失敗", ['filePath' => $oldPs1]);
        }
    }

    // ★ 新しいタスクを登録
    $datetime = \Carbon\Carbon::parse($post->run_datetime)->format('Y-m-d H:i');

    if ($post->action_type === 'program') {
        $program = $post->program_path;
        $args = $post->arguments ?? '';

    $command = 'powershell -Command "' .
        '$action = New-ScheduledTaskAction -Execute \'' . $program . '\' ' .
        ($args !== '' ? ' -Argument \'' . $args . '\'' : '') . '; ' .
        '$trigger = New-ScheduledTaskTrigger -Once -At \'' . $datetime . '\'; ' .
        'Register-ScheduledTask -TaskName \'' . $post->title . '\'' .
        ' -Description \'' . ($post->body ?? '') . '\'' .
        ' -Action $action -Trigger $trigger -Force"';
        
    } elseif ($post->action_type === 'popup') {
        // ★ PowerShellファイル生成
        $filename = 'popup_' . uniqid() . '.ps1';
        $filePath = storage_path('app/' . $filename);
        $popupTitle   = $post->popup_title ?? '通知';
        $popupMessage = $post->popup_message ?? '時間になりました！';
        $script = "(New-Object -ComObject Wscript.Shell).Popup('$popupMessage',0,'$popupTitle',64)";
        if (file_put_contents($filePath, mb_convert_encoding($script, 'CP932', 'UTF-8')) === false) {
            \Log::error("ps1ファイルの書き込み失敗", ['filePath' => $filePath]);
        }


        // ★ ps1_path を更新
        $post->ps1_path = $filePath;
        $post->save();

        // ★ タスク登録
        $command = 'powershell -Command "' .
            '$action = New-ScheduledTaskAction -Execute ' .
            '\'%SystemRoot%\System32\WindowsPowerShell\v1.0\powershell.exe\' ' .
            ' -Argument \'-NoProfile -ExecutionPolicy Bypass -WindowStyle Hidden -File \"' . $filePath . '\"\' ; ' .
            '$trigger = New-ScheduledTaskTrigger -Once -At \'' . $datetime . '\'; ' .
            'Register-ScheduledTask -TaskName \'' . $post->title . '\'' .
            ' -Description \'' . ($post->body ?? '') . '\'' .
            ' -Action $action -Trigger $trigger -Force"';
    }

    exec($command, $output, $result);
    if ($result !== 0) {
        \Log::error("新タスク登録失敗 (update)", [
            'command' => $command,
            'output'  => $output,
        ]);
    }

    return redirect()->route('posts.index', $post->id)->with('success', 'タスクを更新しました');
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

    // ★ タスクスケジューラから削除
    $deleteCommand = 'powershell -Command "Unregister-ScheduledTask -TaskName ' 
        . escapeshellarg($post->title) 
        . ' -Confirm:$false"';
    exec($deleteCommand, $output, $result);

    if ($result !== 0) {
        \Log::error("タスク削除失敗", [
            'command' => $deleteCommand,
            'output'  => $output,
        ]);
        return back()->withErrors('タスクスケジューラからの削除に失敗しました。');
    }

    // ★ ps1ファイル削除（popup の場合）
    if ($post->ps1_path && file_exists($post->ps1_path)) {
        if (!unlink($post->ps1_path)) {
            \Log::warning("ps1ファイル削除失敗", ['filePath' => $post->ps1_path]);
        }
    }

    // ★ DB削除
    $post->delete();

    return redirect()->route('posts.index')->with('success', 'タスクを削除しました');
    }

    public function mypage(Request $request)
    {
        $tab = $request->get('tab', 'history'); // デフォルトは history
        $user = auth()->user();

        if ($tab === 'like') {
            $posts = $user->favoritePosts()->with('user')->orderBy('created_at', 'desc')->paginate(6);// お気に入り投稿
        } else {
            $posts = $user->posts()->with('favorites')->orderBy('created_at', 'desc')->paginate(6);// 自分の投稿
        }

        return view('posts.mypage', compact('posts', 'tab'));
    }
}
