<p>ユーザー名: {{ $user->name }}</p>
<p>作成日: {{ $user->created_at }}</p>
<p>投稿数: {{ $user->posts->count() }}</p>
<p>お気に入り総数: {{ $totalFavorites }}</p>

<a href="{{ route('posts.mypage') }}">アカウントへ</a>
