{{-- 検索フォーム --}}
<form action="{{ route('posts.index') }}" method="GET" style="margin-bottom: 20px;">

    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="タイトルや説明で検索">
        <select name="sort">
        <option value="">新しい順</option>
        <option value="old" {{ $sort === 'old' ? 'selected' : '' }}>古い順</option>
        <option value="favorites" {{ $sort === 'favorites' ? 'selected' : '' }}>人気順</option>
    </select>

    <button type="submit">検索</button>
</form>

@foreach($posts as $post)

    @if ($post->isFavoritedBy(auth()->user()))
        {{-- お気に入り解除 --}}
        <form action="{{ route('favorites.destroy', $post->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" style="color: gold;">★</button>
        </form>
    @else
        {{-- お気に入り登録 --}}
        <form action="{{ route('favorites.store', $post->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="color: gray;">☆</button>
        </form>
    @endif
    <p>お気に入り数: {{ $post->favorites_count }}</p>
    <p>投稿者: {{ $post->user->name }}</p>

    @if ($post->screenshot_path)
        <img src="{{ asset('storage/'.$post->screenshot_path) }}" alt="実行結果" style="width:150px; height:auto;">
    @else
        <span>[画像なし]</span>
    @endif

    <h3>{{ $post->title }}</h3>
    <p>{{ $post->body }}</p>
    <a href="{{ route('posts.show', $post->id) }}">詳細を見る</a>
@endforeach

<div style="margin-top: 20px;">
    {{ $posts->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}{{-- 条件を保持したままページング --}}
</div>

