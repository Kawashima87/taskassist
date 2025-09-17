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
<p>投稿者: {{ $post->user->name }}</p>

@if ($post->screenshot_path)
    <img src="{{ asset('storage/'.$post->screenshot_path) }}" 
            alt="スクショ" 
            style="max-width:300px; height:auto;">
@endif

<p>{{ $post->body }}</p>

<hr>

<dl>
    <dt>実行ファイルのパス</dt>
    <dd>{{ $post->program_path }}</dd>

    <dt>引数</dt>
    <dd>{{ $post->arguments ?? '（なし）' }}</dd>

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

