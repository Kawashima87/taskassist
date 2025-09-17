<h2>お気に入り一覧</h2>

@forelse($posts as $post)
    <div>
        <strong>{{ $post->title }}</strong>
        <p>{{ $post->body }}</p>
        <a href="{{ route('posts.show', $post->id) }}">詳細</a>
    </div>
@empty
    <p>お気に入りはまだありません。</p>
@endforelse