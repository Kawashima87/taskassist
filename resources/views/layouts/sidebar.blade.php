<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'TaskAssist') }}</title>
    @vite([
        'resources/css/app.css',
        'resources/css/components/search.css',
        'resources/css/components/post-card.css',
        'resources/css/components/sidebar.css',
        'resources/css/components/createform.css',
        'resources/css/components/pageTitle.css',
        'resources/css/components/mypage.css',
        'resources/css/components/userTable.css',
        'resources/css/components/ps1.css',
        'resources/css/components/logs.css',
        'resources/css/components/pagination.css',
        'resources/js/app.js'
    ])
</head>
@stack('scripts')
<body class="flex min-h-screen bg-gray-100">

{{-- サイドバー --}}
<aside class="sidebar">
    <div class="sidebar-header">
        TaskAssist
    </div>
    <nav>
        @php
            // ★ アイコン名を追加（icons/*.blade.php のファイル名と対応）
            $links = [
                ['route' => 'posts.index',  'label' => 'ホーム',   'icon' => 'home'],
                ['route' => 'posts.create', 'label' => '新規作成', 'icon' => 'add'],
                ['route' => 'posts.mypage', 'label' => 'アカウント','icon' => 'accountCircle'],
            ];

            $adminLinks = [
                ['route' => 'admin.users', 'label' => 'ユーザー情報', 'icon' => 'manageAccounts'],
                ['route' => 'admin.logs',  'label' => 'エラーログ',   'icon' => 'manageHistory'],
                ['route' => 'admin.ps1',   'label' => 'ps1管理',      'icon' => 'file'],
            ];
        @endphp

        {{-- 通常リンク --}}
        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
               class="{{ request()->routeIs($link['route']) ? 'active' : '' }}">
               @include('icons.' . $link['icon'])
               <span>{{ $link['label'] }}</span>
            </a>
        @endforeach

        {{-- 管理者用リンク --}}
        @if(auth()->check() && auth()->user()->is_admin)
            <div class="admin-section">
                <p class="admin-title">管理者モード</p>
                <div class="admin-links">
                    @foreach ($adminLinks as $link)
                        <a href="{{ route($link['route']) }}"
                           class="{{ request()->routeIs($link['route']) ? 'active' : '' }}">
                           @include('icons.' . $link['icon'])
                           <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ▼ ログアウトリンク --}}
        <form method="POST" action="{{ route('logout') }}" class="logout-form mt-auto">
            @csrf
            <button type="submit">
                @include('icons.logout') {{-- アイコンは icons/logout.blade.php を用意してください --}}
                <span>ログアウト</span>
            </button>
        </form>
    </nav>
</aside>

<main class="ml-64 flex-1 p-6 overflow-y-auto main-content">
    @yield('content')
</main>

{{-- ★ フラッシュメッセージ --}}
@if (session('success'))
    <div id="flash-message-success"
         class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 
                rounded-md bg-green-100 text-green-800 px-6 py-3 shadow-lg 
                transition-opacity duration-500">
        ✅ {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div id="flash-message-error"
         class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 
                rounded-md bg-red-100 text-red-800 px-6 py-3 shadow-lg 
                transition-opacity duration-500">
        ❌
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const successBox = document.getElementById("flash-message-success");
        const errorBox = document.getElementById("flash-message-error");
        [successBox, errorBox].forEach(box => {
            if (box) {
                setTimeout(() => {
                    box.style.opacity = "0";
                    setTimeout(() => box.remove(), 500);
                }, 5000);
            }
        });
    });
</script>

</body>
</html>
