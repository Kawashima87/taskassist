@extends('layouts.sidebar')

@section('content')
<h1 class="page-title">新規作成</h1>

<form id="taskForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="create-form">
    @csrf

    <div class="form-group">
        <label>画像</label>
        <input type="file" name="screenshot">
    </div>

    <div class="form-group">
        <label>タイトル</label>
        <input type="text" name="title">
    </div>

    <div class="form-group">
        <label>説明</label>
        <textarea name="body" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label>実行日時</label>
        <input type="datetime-local" name="run_datetime">
    </div>

    <div class="form-group">
        <label>操作種別</label>
        <select name="action_type" id="action_type" onchange="toggleFields()">
            <option value="program" selected>アプリを実行する</option>
            <option value="popup">メッセージを表示する</option>
        </select>
    </div>

    {{-- アプリ実行用フィールド --}}
    <div id="program_fields" class="form-group">
        <label>パス</label>
        <input type="text" id="program_path" name="program_path">

        <label>引数</label>
        <input type="text" name="arguments">
    </div>

    {{-- ポップアップ用フィールド --}}
    <div id="popup_fields" class="form-group" style="display:none;">
        <label>ポップアップタイトル</label>
        <input type="text" id="popup_title" name="popup_title">

        <label>ポップアップ内容</label>
        <input type="text" id="popup_message" name="popup_message">
    </div>

    <input type="hidden" name="enabled" value="1">

    <div class="form-buttons">
        <a href="{{ route('posts.index') }}">
            <button type="button" class="cancel-button">キャンセル</button>
        </a>
        <button type="submit" class="send-button">
            @include('icons.send')
        </button>
    </div>
</form>
@endsection
