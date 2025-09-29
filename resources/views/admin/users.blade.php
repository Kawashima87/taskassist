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
<table class="user-table">
    <thead>
        <tr>
            <th>ユーザー名</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <td>
                <div class="user-info">
                    <img src="{{ $user->icon_url }}" alt="ユーザーアイコン">
                    <span>{{ $user->name }}</span>
                </div>
            </td>
            <td>
                {{-- 詳細 --}}
                <button type="button" class="detail-btn" onclick="showUserDetail({{ $user->id }})">
                    詳細
                </button>

                {{-- 削除（管理者以外） --}}
                @if (!$user->is_admin)
                    <form action="{{ route('admin.users.delete', $user->id) }}" 
                          method="POST" style="display:inline;"
                          onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-btn">削除</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
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
