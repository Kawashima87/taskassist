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
    @forelse($posts as $post)
        <div>
            <strong>{{ $post->title }}</strong>
            <p>{{ $post->body }}</p>
            <a href="{{ route('posts.show', $post->id) }}">詳細</a>
        </div>
    @empty
        <p>投稿はまだありません。</p>
    @endforelse
<div style="margin-top: 20px;">
    {{ $posts->appends(['tab' => $tab])->links() }}{{-- タブがきりかわらないように --}}
</div>

@elseif ($tab === 'like')
    <h3>お気に入り一覧</h3>
    @forelse($posts as $post)
        <div>
            <strong>{{ $post->title }}</strong>
            <p>{{ $post->body }}</p>
            <a href="{{ route('posts.show', $post->id) }}">詳細</a>
        </div>
    @empty
        <p>お気に入りはまだありません。</p>
    @endforelse
<div style="margin-top: 20px;">
    {{ $posts->appends(['tab' => $tab])->links() }}{{-- タブがきりかわらないように --}}
</div>

@endif