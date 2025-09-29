@extends('layouts.sidebar')

@section('content')
    <h1 class="page-title">投稿一覧</h1>

    {{-- 検索フォーム --}}
    <form action="{{ route('posts.index') }}" method="GET" class="search-bar">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="タイトルや説明で検索">

        {{-- 並び替えドロップダウン --}}
        <div class="custom-dropdown">
            <input type="hidden" name="sort" id="sortInput" value="{{ $sort ?? '' }}">
            <button type="button" class="dropdown-toggle" onclick="toggleDropdown()">
                {{ ['old' => '古い順', 'favorites' => '人気順'][$sort] ?? '新しい順' }}
                <span class="arrow">▼</span>
            </button>
            <ul class="dropdown-menu" id="dropdownMenu">
                <li onclick="selectOption('')">新しい順</li>
                <li onclick="selectOption('old')">古い順</li>
                <li onclick="selectOption('favorites')">人気順</li>
            </ul>
        </div>

        <button type="submit" class="search-button">
            @include('icons.search')
        </button>
    </form>

    <hr class="divider">

    {{-- 投稿一覧 --}}
    <div class="post-list">
        @foreach($posts as $post)
            <div class="post-card">

                {{-- ヘッダー --}}
                <div class="post-card__header">
                    <img src="{{ $post->user->icon_url }}" alt="ユーザーアイコン" class="post-card__icon">
                    <span class="post-card__username">{{ $post->user->name }}</span>
                </div>

                {{-- ファーストコンテンツ --}}
                <div class="post-card__first">
                    <div class="post-card__image">
                        @if ($post->screenshot_path)
                            <img src="{{ asset('storage/' . $post->screenshot_path) }}" alt="スクショ">
                        @else
                            <span>[画像なし]</span>
                        @endif
                    </div>

                    <div class="post-card__main">
                        {{-- タイトル（検索バー風） --}}
                        <div class="title-box">
                            <h3 class="post-card__title">{{ $post->title }}</h3>
                        </div>

                        {{-- 説明 --}}
                        <div class="description-box">
                            <div class="description-label">説明</div>
                            <p class="post-card__description">{{ $post->body }}</p>
                        </div>
                    </div>
                </div>

                {{-- 詳細（開閉式） --}}
                <div class="post-card__second">
                    <h4 class="detail-title">詳細情報</h4>
                    <div class="detail-grid">

                        {{-- 操作種別 --}}
                        <div class="detail-item">
                            <span class="label">操作種別</span>
                            <span class="value">{{ $post->action_type === 'program' ? 'アプリ実行' : 'ポップアップ通知' }}</span>
                        </div>

                        {{-- 種別ごとの追加情報 --}}
                        @if ($post->action_type === 'program')
                            <div class="detail-item">
                                <span class="label">実行ファイルのパス</span>
                                <span class="value">{{ $post->program_path ?? '（なし）' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">引数</span>
                                <span class="value">{{ $post->arguments ?? '（なし）' }}</span>
                            </div>
                        @else
                            <div class="detail-item">
                                <span class="label">ポップアップタイトル</span>
                                <span class="value">{{ $post->popup_title ?? '（なし）' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">ポップアップメッセージ</span>
                                <span class="value">{{ $post->popup_message ?? '（なし）' }}</span>
                            </div>
                        @endif

                        <div class="detail-item">
                            <span class="label">実行日時</span>
                            <span class="value">{{ $post->run_datetime }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">有効状態</span>
                            <span class="value">{{ $post->enabled ? '有効' : '停止中' }}</span>
                        </div>
                    </div>
                </div>

                {{-- フッター --}}
                <div class="post-card__footer">

                    {{-- 左：お気に入り --}}
                    <div class="post-card__footer-left">
                        @if ($post->isFavoritedBy(auth()->user()))
                            <form action="{{ route('favorites.destroy', $post->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="favorite-button active">
                                    @include('icons.heart-solid')
                                </button>
                            </form>
                        @else
                            <form action="{{ route('favorites.store', $post->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="favorite-button">
                                    @include('icons.heart-outline')
                                </button>
                            </form>
                        @endif
                        <span>{{ $post->favorites_count }}</span>
                    </div>

                    {{-- 中央：もっと見る --}}
                    <div class="post-card__footer-center">
                        <button type="button" class="more-button" onclick="toggleDetails(this)">もっと見る</button>
                    </div>

                    {{-- 右：編集削除 --}}
                    <div class="post-card__footer-right">
                        @auth
                            @if ($post->user_id === auth()->id())
                                <a href="{{ route('posts.edit', $post->id) }}" class="edit-button">
                                    @include('icons.edit')
                                </a>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-button" onclick="return confirm('本当に削除しますか？')">
                                        @include('icons.trash')
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    {{-- ページネーション --}}
    <div class="pagination-area">
        {{ $posts->appends(['search' => $search ?? '', 'sort' => $sort ?? ''])->links() }}
    </div>
@endsection

@push('scripts')
<script>
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('show');
    }
    function selectOption(value) {
        document.getElementById('sortInput').value = value;
        document.querySelector('.dropdown-toggle').childNodes[0].nodeValue =
            value === 'old' ? '古い順' :
            value === 'favorites' ? '人気順' : '新しい順';
        document.getElementById('dropdownMenu').classList.remove('show');
    }
    function toggleDetails(button) {
        const card = button.closest('.post-card');
        const details = card.querySelector('.post-card__second');
        details.classList.toggle('open');
        button.textContent = details.classList.contains('open') ? '閉じる' : 'もっと見る';
    }
</script>
@endpush
