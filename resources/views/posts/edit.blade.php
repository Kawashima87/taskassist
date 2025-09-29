@extends('layouts.sidebar')

@section('content')
<h1 class="page-title">タスク編集</h1>

<form id="editTaskForm" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="create-form">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>画像</label>
        @if ($post->screenshot_path)
            <p>現在: <img src="{{ asset('storage/'.$post->screenshot_path) }}" alt="スクショ" style="width:100px;"></p>
        @endif
        <input type="file" name="screenshot">
    </div>

    <div class="form-group">
        <label>タイトル</label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
    </div>

    <div class="form-group">
        <label>説明</label>
        <textarea name="body" rows="4">{{ old('body', $post->body) }}</textarea>
    </div>

    <div class="form-group">
        <label>実行日時</label>
        <input type="datetime-local" name="run_datetime"
               value="{{ old('run_datetime', \Carbon\Carbon::parse($post->run_datetime)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="form-group">
        <label>操作種別</label>
        <select name="action_type" id="action_type" onchange="toggleFields()">
            <option value="program" {{ old('action_type', $post->action_type) === 'program' ? 'selected' : '' }}>アプリを実行する</option>
            <option value="popup" {{ old('action_type', $post->action_type) === 'popup' ? 'selected' : '' }}>メッセージを表示する</option>
        </select>
    </div>

    {{-- アプリ実行用フィールド --}}
    <div id="program_fields" class="form-group" style="{{ old('action_type', $post->action_type) === 'program' ? '' : 'display:none;' }}">
        <label>パス</label>
        <input type="text" id="program_path" name="program_path" value="{{ old('program_path', $post->program_path) }}">

        <label>引数</label>
        <input type="text" name="arguments" value="{{ old('arguments', $post->arguments) }}">
    </div>

    {{-- ポップアップ用フィールド --}}
    <div id="popup_fields" class="form-group" style="{{ old('action_type', $post->action_type) === 'popup' ? '' : 'display:none;' }}">
        <label>ポップアップタイトル</label>
        <input type="text" id="popup_title" name="popup_title" value="{{ old('popup_title', $post->popup_title) }}">

        <label>ポップアップ内容</label>
        <input type="text" id="popup_message" name="popup_message" value="{{ old('popup_message', $post->popup_message) }}">
    </div>

    <div class="form-buttons">
        <a href="{{ route('posts.index') }}">
            <button type="button" class="cancel-button">キャンセル</button>
        </a>
        <button type="submit" class="send-button">
            @include('icons.update')
        </button>
    </div>
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
            alert("アプリを実行する場合は『パス』を入力してください。");
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
