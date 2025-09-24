@extends('layouts.sidebar')

@section('content')
<h1 class="text-xl font-bold mb-4">タスク編集</h1>

<form id="editTaskForm" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label>タイトル:</label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
    </div>

    <div>
        <label>説明:</label>
        <textarea name="body">{{ old('body', $post->body) }}</textarea>
    </div>

    <div>
        <label>実行日時:</label>
        <input type="datetime-local" name="run_datetime"
               value="{{ old('run_datetime', \Carbon\Carbon::parse($post->run_datetime)->format('Y-m-d\TH:i')) }}">
    </div>

    {{-- 操作種別 --}}
    <div>
        <label>操作種別:</label>
        <select name="action_type" id="action_type" onchange="toggleFields()">
            <option value="program" {{ old('action_type', $post->action_type) === 'program' ? 'selected' : '' }}>アプリを実行する</option>
            <option value="popup" {{ old('action_type', $post->action_type) === 'popup' ? 'selected' : '' }}>メッセージを表示する</option>
        </select>
    </div>

    {{-- アプリ実行用フィールド --}}
    <div id="program_fields" style="{{ old('action_type', $post->action_type) === 'program' ? '' : 'display:none;' }}">
        <label>実行ファイルのパス:</label>
        <input type="text" id="program_path" name="program_path" value="{{ old('program_path', $post->program_path) }}">
        <br>
        <label>引数:</label>
        <input type="text" name="arguments" value="{{ old('arguments', $post->arguments) }}">
    </div>

    {{-- ポップアップ用フィールド --}}
    <div id="popup_fields" style="{{ old('action_type', $post->action_type) === 'popup' ? '' : 'display:none;' }}">
        <label>ポップアップタイトル:</label>
        <input type="text" id="popup_title" name="popup_title" value="{{ old('popup_title', $post->popup_title) }}">
        <br>
        <label>ポップアップ内容:</label>
        <input type="text" id="popup_message" name="popup_message" value="{{ old('popup_message', $post->popup_message) }}">
    </div>

    <div>
        <label>スクリーンショット:</label>
        @if ($post->screenshot_path)
            <p>現在: <img src="{{ asset('storage/'.$post->screenshot_path) }}" alt="スクショ" style="width:100px;"></p>
        @endif
        <input type="file" name="screenshot">
    </div>

    <button type="submit">更新</button>
    <a href="{{ route('posts.show', $post->id) }}">キャンセル</a>
</form>

<script>
function toggleFields() {
    const type = document.getElementById('action_type').value;
    document.getElementById('program_fields').style.display = (type === 'program') ? 'block' : 'none';
    document.getElementById('popup_fields').style.display = (type === 'popup') ? 'block' : 'none';
}

// 入力チェック
document.getElementById('editTaskForm').addEventListener('submit', function (e) {
    const type = document.getElementById('action_type').value;

    if (type === 'program') {
        const programPath = document.getElementById('program_path').value.trim();
        if (!programPath) {
            alert("アプリを実行する場合は『実行ファイルのパス』を入力してください。");
            e.preventDefault();
        }
    }

    if (type === 'popup') {
        const title = document.getElementById('popup_title').value.trim();
        const message = document.getElementById('popup_message').value.trim();
        if (!title || !message) {
            alert("メッセージを表示する場合は『タイトル』と『内容』を入力してください。");
            e.preventDefault();
        }
    }
});
</script>

@endsection
