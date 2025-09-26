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
                $links = [
                    ['route' => 'posts.index',  'label' => 'ホーム'],
                    ['route' => 'posts.create', 'label' => '新規作成'],
                    ['route' => 'posts.mypage', 'label' => 'アカウント'],
                ];

                $adminLinks = [
                    ['route' => 'admin.users', 'label' => 'ユーザー情報'],
                    ['route' => 'admin.logs',  'label' => 'エラーログ'],
                    ['route' => 'admin.ps1',   'label' => 'ps1管理'],
                ];
            @endphp

            {{-- 通常リンク --}}
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                class="{{ request()->routeIs($link['route']) ? 'active' : '' }}">
                {{ $link['label'] }}
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
                            {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </nav>

</aside>


<main class="ml-64 flex-1 p-6 overflow-y-auto main-content">
    @yield('content')
</main>

{{-- ★ フラッシュメッセージ（上中央にオーバーレイ表示） --}}
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

{{-- ★ 自動で5秒後に消すスクリプト --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const successBox = document.getElementById("flash-message-success");
        const errorBox = document.getElementById("flash-message-error");

        [successBox, errorBox].forEach(box => {
            if (box) {
                setTimeout(() => {
                    box.style.opacity = "0";      // フェードアウト
                    setTimeout(() => box.remove(), 500); // 完全に消す
                }, 5000); // 5秒後
            }
        });
    });
</script>




</body>
</html>
