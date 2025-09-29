<div class="user-detail-card">
    <h2 class="user-detail-title">ユーザー詳細</h2>

    <div class="user-detail-item">
        <span class="label">ユーザー名</span>
        <span class="value">{{ $user->name }}</span>
    </div>

    <div class="user-detail-item">
        <span class="label">作成日</span>
        <span class="value">{{ $user->created_at->format('Y-m-d') }}</span>
    </div>

    <div class="user-detail-item">
        <span class="label">投稿数</span>
        <span class="value">{{ $user->posts->count() }}</span>
    </div>

    <div class="user-detail-item">
        <span class="label">お気に入り総数</span>
        <span class="value">{{ $totalFavorites }}</span>
    </div>
</div>
