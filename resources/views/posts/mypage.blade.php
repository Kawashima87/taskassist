<h2>My Page</h2>

{{-- トグルボタン --}}
<div style="margin-bottom: 20px;">
    <a href="{{ route('posts.mypage', ['tab' => 'history']) }}"
        style="padding: 10px; {{ $tab === 'history' ? 'background: orange; color: white;' : 'background: #ddd;' }}">
        History
    </a>
    <a href="{{ route('posts.mypage', ['tab' => 'like']) }}"
        style="padding: 10px; {{ $tab === 'like' ? 'background: orange; color: white;' : 'background: #ddd;' }}">
        Like
    </a>
</div>

{{-- タブ切り替え --}}
@if ($tab === 'history')
    <h3>自分の投稿一覧</h3>
@elseif ($tab === 'like')
    <h3>お気に入り一覧</h3>
@endif

@forelse($posts as $post)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">

        {{-- お気に入りボタン --}}
        @if ($post->isFavoritedBy(auth()->user()))
            <form action="{{ route('favorites.destroy', $post->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" style="color: gold;">★</button>
            </form>
        @else
            <form action="{{ route('favorites.store', $post->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="color: gray;">☆</button>
            </form>
        @endif

        <p>お気に入り数: {{ $post->favorites_count ?? $post->favorites->count() }}</p>
        <p>投稿者: {{ $post->user->name }}</p>

        @if ($post->screenshot_path)
            <img src="{{ asset('storage/'.$post->screenshot_path) }}" alt="実行結果" style="width:150px; height:auto;">
        @else
            <span>[画像なし]</span>
        @endif

        <h3>{{ $post->title }}</h3>
        <p>{{ $post->body }}</p>
        <a href="{{ route('posts.show', $post->id) }}">詳細を見る</a>
    </div>
@empty
    @if ($tab === 'history')
        <p>投稿はまだありません。</p>
    @else
        <p>お気に入りはまだありません。</p>
    @endif
@endforelse

<div style="margin-top: 20px;"> {{-- タブがきりかわらないように --}}
    {{ $posts->appends([
        'tab' => $tab,
        'search' => $search ?? '',
        'sort' => $sort ?? ''
    ])->links() }}
</div>