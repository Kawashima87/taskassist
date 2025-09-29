@extends('layouts.sidebar')

@section('content')
<h1 class="page-title">アカウント</h1>

{{-- ユーザー情報 --}}
<div class="mypage-user">
    <img src="{{ auth()->user()->icon_url }}" 
         alt="ユーザーアイコン" 
         class="mypage-user__icon">
    <span class="mypage-user__name">{{ auth()->user()->name }}</span>
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
                    <h3 class="post-card__title">{{ $post->title }}</h3>
                    <p class="post-card__description">{{ $post->body }}</p>
                </div>
            </div>

            {{-- セカンドコンテンツ（詳細情報） --}}
            <div class="post-card__second">
                <dl>
                    <dt>操作種別</dt>
                    <dd>{{ $post->action_type === 'program' ? 'アプリ実行' : 'ポップアップ通知' }}</dd>

                    @if ($post->action_type === 'program')
                        <dt>実行ファイルのパス</dt>
                        <dd>{{ $post->program_path ?? '（なし）' }}</dd>
                        <dt>引数</dt>
                        <dd>{{ $post->arguments ?? '（なし）' }}</dd>
                    @else
                        <dt>ポップアップタイトル</dt>
                        <dd>{{ $post->popup_title ?? '（なし）' }}</dd>
                        <dt>ポップアップメッセージ</dt>
                        <dd>{{ $post->popup_message ?? '（なし）' }}</dd>
                    @endif

                    <dt>実行日時</dt>
                    <dd>{{ $post->run_datetime }}</dd>
                    <dt>有効状態</dt>
                    <dd>{{ $post->enabled ? '有効' : '停止中' }}</dd>
                </dl>
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
</script>
@endpush
