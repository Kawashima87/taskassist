@extends('layouts.sidebar')

@section('content')
<h1 class="text-xl font-bold mb-4">新規作成</h1>

<form id="taskForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    画像 <input type="file" name="screenshot"><br>
    タイトル <input type="text" name="title"><br>
    説明 <input type="text" name="body"><br>
    実行日時 <input type="datetime-local" name="run_datetime"><br>

    {{-- 操作種別 --}}
    操作種別
    <select name="action_type" id="action_type" onchange="toggleFields()">
        <option value="program" selected>アプリを実行する</option>
        <option value="popup">メッセージを表示する</option>
    </select><br>

    {{-- アプリ実行用フィールド --}}
    <div id="program_fields">
        パス <input type="text" id="program_path" name="program_path"><br>
        引数 <input type="text" name="arguments"><br>
    </div>

    {{-- ポップアップ用フィールド --}}
    <div id="popup_fields" style="display:none;">
        ポップアップタイトル <input type="text" id="popup_title" name="popup_title"><br>
        ポップアップ内容 <input type="text" id="popup_message" name="popup_message"><br>
    </div>

    <input type="hidden" name="enabled" value="1">

    <a href="{{ route('posts.index') }}"><button type="button">キャンセル</button></a>
    <button type="submit">実行</button>
</form>

<script>
function toggleFields() {
    const type = document.getElementById('action_type').value;
    document.getElementById('program_fields').style.display = (type === 'program') ? 'block' : 'none';
    document.getElementById('popup_fields').style.display = (type === 'popup') ? 'block' : 'none';
}

// フロント側の入力チェック
document.getElementById('taskForm').addEventListener('submit', function (e) {
    const type = document.getElementById('action_type').value;

    if (type === 'program') {
        const programPath = document.getElementById('program_path').value.trim();
        if (!programPath) {
            alert("アプリを実行する場合は『パス』を入力してください。");
            e.preventDefault(); // 送信中止
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
