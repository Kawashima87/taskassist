@extends('layouts.sidebar')

@section('content')
<h1 class="page-title">アカウント</h1>

{{-- ユーザー情報 --}}
<div class="mypage-user">
    <img src="{{ auth()->user()->icon_url }}" 
         alt="ユーザーアイコン" 
         class="mypage-user__icon">
    <span class="mypage-user__name">{{ auth()->user()->name }}</span>

    {{-- 編集ボタン --}}
    <button type="button" class="edit-button" onclick="openEditModal()">
        @include('icons.edit')
    </button>
</div>

{{-- モーダル --}}
<div id="editModal" class="modal hidden">
    <div class="modal-content">
        <h2>プロフィール編集</h2>
        <form action="{{ route('user.update') }}" method="POST">
            @csrf
            @method('PUT')

            {{-- 名前入力 --}}
            <div>
                <label for="name">名前</label>
                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required>
            </div>

            {{-- アイコン選択 --}}
            <div>
                <label>アイコン</label>
                <div class="icon-grid">
                    @foreach($icons as $icon)
                        <label class="icon-option">
                            <input type="radio" name="icon" value="{{ $icon }}"
                                {{ auth()->user()->icon_path === 'icons/'.$icon ? 'checked' : '' }}>
                            <img src="{{ asset('storage/icons/'.$icon) }}" 
                                class="icon-preview" 
                                alt="icon">
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="modal-actions">
                <button type="submit">更新</button>
                <button type="button" onclick="closeEditModal()">キャンセル</button>
            </div>
        </form>
    </div>
</div>

{{-- スライド式タブ --}}
<div class="tab-switcher">
    <a href="{{ route('posts.mypage', ['tab' => 'history']) }}"
       class="tab-btn {{ $tab === 'history' ? 'active' : '' }}">
        投稿履歴
    </a>
    <a href="{{ route('posts.mypage', ['tab' => 'like']) }}"
       class="tab-btn {{ $tab === 'like' ? 'active' : '' }}">
        お気に入り
    </a>
    <div class="tab-indicator {{ $tab }}"></div>
</div>

{{-- 投稿カード（index.blade.phpと同じUI） --}}
<div class="post-list">
    @forelse($posts as $post)
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


            {{-- セカンドコンテンツ（詳細情報：もっと見るで開閉） --}}
            <div class="post-card__second">
                <h4 class="detail-title">詳細情報</h4>
                <div class="detail-grid">
                    <div class="detail-item">
                        <span class="label">操作種別</span>
                        <span class="value">{{ $post->action_type === 'program' ? 'アプリ実行' : 'ポップアップ通知' }}</span>
                    </div>

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
                    <span>{{ $post->favorites_count ?? $post->favorites->count() }}</span>
                </div>

                {{-- 中央：もっと見る --}}
                <div class="post-card__footer-center">
                    <button type="button" class="more-button"
                            onclick="toggleDetails(this)">もっと見る</button>
                </div>

                {{-- 右：編集削除（自分の投稿だけ） --}}
                <div class="post-card__footer-right">
                    @auth
                        @if ($post->user_id === auth()->id())
                            <a href="{{ route('posts.edit', $post->id) }}" class="edit-button">
                                @include('icons.edit')
                            </a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button"
                                        onclick="return confirm('本当に削除しますか？')">
                                    @include('icons.trash')
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @empty
        @if ($tab === 'history')
            <p>投稿はまだありません。</p>
        @else
            <p>お気に入りはまだありません。</p>
        @endif
    @endforelse
</div>

{{-- ページネーション --}}
<div class="pagination-area">
    {{ $posts->appends([
        'tab' => $tab,
        'search' => $search ?? '',
        'sort' => $sort ?? ''
    ])->links() }}
</div>
@endsection

@push('scripts')
<script>
    // 詳細開閉制御
    function toggleDetails(button) {
        const card = button.closest('.post-card');
        const details = card.querySelector('.post-card__second');
        details.classList.toggle('open');
        button.textContent = details.classList.contains('open') ? '閉じる' : 'もっと見る';
    }
    function openEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endpush
