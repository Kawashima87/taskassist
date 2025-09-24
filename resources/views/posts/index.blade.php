@extends('layouts.sidebar')

@section('content')
    <h1 class="text-xl font-bold mb-4">投稿一覧</h1>
    {{-- 投稿リスト --}}

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
@endforeach

<div style="margin-top: 20px;">
    {{ $posts->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}{{-- 条件を保持したままページング --}}
</div>

@endsection