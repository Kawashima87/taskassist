@extends('layouts.sidebar')

@section('content')

<h1 class="page-title">ユーザー管理</h1>

{{-- 検索フォーム --}}
<form method="GET" action="{{ route('admin.users') }}" class="search-bar">
    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="ユーザー名で検索">

    <button type="submit" class="search-button">
        @include('icons.search')
    </button>
</form>


{{-- ユーザー一覧 --}}
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ユーザー名</th>
        <th>操作</th>
    </tr>
    @foreach($users as $user)
        <tr>
            <td>
                <p>
                    <img src="{{$user->icon_url }}" 
                        alt="ユーザーアイコン" 
                        style="width:50px; height:50px; border-radius:50%; object-fit:cover; display:inline-block; vertical-align:middle;">
                    {{ $user->name }}
                </p>
            </td>
            <td>
                {{-- 詳細 --}}
                <button type="button" onclick="showUserDetail({{$user->id}})">詳細</button>

                {{-- 削除（管理者以外） --}}
                @if (!$user->is_admin)
                    <form action="{{ route('admin.users.delete', $user->id) }}" 
                          method="POST" 
                          style="display:inline;" 
                          onsubmit="return confirm(&quot;本当に削除しますか？&quot;);">
                        @csrf
                        @method('DELETE')
                        <button type="submit">削除</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</table>

<div style="margin-top: 20px;">
    {{ $users->appends(['search' => $search ?? ''])->links() }}
</div>

{{-- === モーダルをここに設置 === --}}
<div id="userDetailModal" 
     style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%);
            background:white; border:2px solid black; padding:20px; width:400px; z-index:1000;">
    <div id="userDetailContent">読み込み中...</div>
    <button onclick="closeModal()">閉じる</button>
</div>

<script>
function showUserDetail(userId) {
    fetch(`/admin/users/${userId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('userDetailContent').innerHTML = html;
            document.getElementById('userDetailModal').style.display = 'block';
        })
        .catch(() => {
            document.getElementById('userDetailContent').innerHTML = "読み込み失敗";
        });
}

function closeModal() {
    document.getElementById('userDetailModal').style.display = 'none';
}
</script>

@endsection
