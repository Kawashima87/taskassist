<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'TaskAssist') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen bg-gray-100">

    {{-- サイドバー --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <div class="p-4 text-2xl font-bold border-b border-gray-700">
            TaskAssist
        </div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('posts.index') }}" class="block px-3 py-2 rounded hover:bg-gray-700">Home</a>
            <a href="{{ route('posts.create') }}" class="block px-3 py-2 rounded hover:bg-gray-700">New Create</a>
            <a href="{{ route('posts.mypage') }}" class="block px-3 py-2 rounded hover:bg-gray-700">My Page</a>

            {{-- 管理者用メニュー --}}
            @if(auth()->check() && auth()->user()->is_admin)
                <div>
                    <p class="px-3 py-2 font-semibold">管理者</p>
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-1 rounded hover:bg-gray-700">ユーザー情報</a>
                    <a href="{{ route('admin.logs') }}" class="block px-3 py-1 rounded hover:bg-gray-700">エラーログ</a>
                    <a href="{{ route('admin.ps1') }}" class="block px-3 py-1 rounded hover:bg-gray-700">残ps1管理</a>
                </div>
            @endif
        </nav>
    </aside>

    {{-- メインコンテンツ --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</body>
</html>
