@extends('layouts.sidebar')

@section('content')
<h1 class="text-xl font-bold mb-4">アカウント</h1>


<p>
    <img src="{{auth()->user()->icon_url }}" 
        alt="ユーザーアイコン" 
        style="width:150px; height:150px; border-radius:50%; object-fit:cover; display:inline-block; vertical-align:middle;">
    {{ auth()->user()->name }}
</p>

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
        <p>
            <img src="{{ $post->user->icon_url }}" 
                alt="ユーザーアイコン" 
                style="width:50px; height:50px; border-radius:50%; object-fit:cover; display:inline-block; vertical-align:middle;">
            {{ $post->user->name }}
        </p>

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
@endsection