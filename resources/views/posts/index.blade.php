@extends('layouts.sidebar')

@section('content')
    {{-- ページタイトル --}}
    <h1 class="page-title">投稿一覧</h1>

    {{-- 検索フォーム --}}
    <form action="{{ route('posts.index') }}" method="GET" class="search-bar">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="タイトルや説明で検索">

    {{-- カスタムドロップダウン --}}
    <div class="custom-dropdown">
        <input type="hidden" name="sort" id="sortInput" value="{{ $sort ?? '' }}">
        <button type="button" class="dropdown-toggle" onclick="toggleDropdown()">
            {{ $sort === 'old' ? '古い順' : ($sort === 'favorites' ? '人気順' : '新しい順') }}
            <span class="arrow">▼</span>
        </button>
        <ul class="dropdown-menu" id="dropdownMenu">
            <li onclick="selectOption('')">新しい順</li>
            <li onclick="selectOption('old')">古い順</li>
            <li onclick="selectOption('favorites')">人気順</li>
        </ul>
    </div>


        <button type="submit" class="search-button">検索</button>
    </form>

    {{-- 区切り線 --}}
    <hr class="divider">

    {{-- 投稿一覧（Gridで整列） --}}
    <div class="post-list">
        @foreach($posts as $post)
            <div class="post-card">
                <div class="post-card__header">
                    <img src="{{ $post->user->icon_url }}" alt="ユーザーアイコン" class="post-card__icon">
                    <span class="post-card__username">{{ $post->user->name }}</span>
                </div>

                <div class="post-card__image">
                    @if ($post->screenshot_path)
                        <img src="{{ asset('storage/' . $post->screenshot_path) }}" alt="スクショ">
                    @else
                        <span>[画像なし]</span>
                    @endif
                </div>

                <h3 class="post-card__title">{{ $post->title }}</h3>
                <p class="post-card__description">{{ $post->body }}</p>

                <div class="post-card__footer">
                    @if ($post->isFavoritedBy(auth()->user()))
                        <form action="{{ route('favorites.destroy', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="favorite-button active">★</button>
                        </form>
                    @else
                        <form action="{{ route('favorites.store', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="favorite-button">☆</button>
                        </form>
                    @endif
                    <span>{{ $post->favorites_count }}</span>
                    <a href="{{ route('posts.show', $post->id) }}" class="detail-button">詳細</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination-area">
        {{ $posts->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}
    </div>
    <script>
    function toggleDropdown() {
        document.getElementById('dropdownMenu').style.display =
            document.getElementById('dropdownMenu').style.display === 'block' ? 'none' : 'block';
    }

    function selectOption(value) {
        document.getElementById('sortInput').value = value;
        document.querySelector('.dropdown-toggle').childNodes[0].nodeValue =
            value === 'old' ? '古い順' :
            value === 'favorites' ? '人気順' : '新しい順';
        document.getElementById('dropdownMenu').style.display = 'none';
    }
    </script>

@endsection
