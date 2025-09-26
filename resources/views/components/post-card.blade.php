<div class="post-card">
    {{-- 投稿者情報 --}}
    <div class="post-card__user">
        <img src="{{ $userIcon }}" alt="ユーザーアイコン" class="post-card__icon">
        <span>{{ $userName }}</span>
    </div>

    {{-- 画像 --}}
    @if ($image)
        <div class="post-card__image">
            <img src="{{ $image }}" alt="スクショ">
        </div>
    @else
        <div class="post-card__image--none">[画像なし]</div>
    @endif

    {{-- タイトル & 本文 --}}
    <h3 class="post-card__title">{{ $title }}</h3>
    <p class="post-card__body">{{ $body }}</p>

    {{-- お気に入り数 --}}
    <p class="post-card__favorites">★ {{ $favoritesCount }}</p>
</div>
