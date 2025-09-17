@foreach($posts as $post)
    <form action="" method="POST" style="display:inline;">
        @csrf
        <button type="button">☆</button>
    </form>

    @if ($post->screenshot_path)
        <img src="{{ asset('storage/'.$post->screenshot_path) }}" alt="実行結果" style="width:150px; height:auto;">
    @else
        <span>[画像なし]</span>
    @endif

    <h3>{{ $post->title }}</h3>
    <p>{{ $post->body }}</p>
    <a href="{{ route('posts.show', $post->id) }}">詳細を見る</a>
@endforeach
