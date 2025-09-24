@extends('layouts.sidebar')

@section('content')
<h1 class="text-xl font-bold mb-4">投稿詳細</h1>

<h2>{{ $post->title }}</h2>
@if ($post->isFavoritedBy(auth()->user()))
    {{-- お気に入り解除 --}}
    <form action="{{ route('favorites.destroy', $post->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" style="color: gold;">★ お気に入り解除</button>
    </form>
@else
    {{-- お気に入り登録 --}}
    <form action="{{ route('favorites.store', $post->id) }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" style="color: gray;">☆ お気に入り</button>
    </form>
@endif
<p>
    <img src="{{ $post->user->icon_url }}" 
        alt="ユーザーアイコン" 
        style="width:50px; height:50px; border-radius:50%; object-fit:cover; display:inline-block; vertical-align:middle,;">
    {{ $post->user->name }}
<p>
@if ($post->screenshot_path)
    <div style="width:450px; height:250px; border-radius:15px; background:#ffffff; display:flex; align-items:center; justify-content:center; overflow:hidden; border:3px solid #000;">
        <img src="{{ asset('storage/' . $post->screenshot_path) }}"
             alt="スクショ"
             style="max-width:100%; max-height:100%; object-fit:contain;">
    </div>
@else
    <div style="width:450px; height:250px; border-radius:15px; background:#ffffff; display:flex; align-items:center; justify-content:center; color:#555; border:3px solid #000;">
        [画像なし]
    </div>
@endif


<p>{{ $post->body }}</p>

<hr>

<dl>
<dt>操作種別</dt>
<dd>{{ $post->action_type === 'program' ? 'アプリ実行' : 'ポップアップ通知' }}</dd>

@if ($post->action_type === 'program')
    <dt>実行ファイルのパス</dt>
    <dd>{{ $post->program_path ?? '（なし）' }}</dd>

    <dt>引数</dt>
    <dd>{{ $post->arguments ?? '（なし）' }}</dd>
@elseif ($post->action_type === 'popup')
    <dt>ポップアップタイトル</dt>
    <dd>{{ $post->popup_title ?? '（なし）' }}</dd>

    <dt>ポップアップメッセージ</dt>
    <dd>{{ $post->popup_message ?? '（なし）' }}</dd>
@endif


    <dt>実行日時</dt>
    <dd>{{ $post->run_datetime }}</dd>

    <dt>有効状態</dt>
    <dd>{{ $post->enabled ? '有効' : '停止中' }}</dd>
</dl>


<a href="{{ route('posts.index') }}">← 一覧に戻る</a>
@auth
    @if ($post->user_id === auth()->id())
        <a href="{{ route('posts.edit', $post->id) }}">編集</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
        </form>
    @endif
@endauth
@endsection

