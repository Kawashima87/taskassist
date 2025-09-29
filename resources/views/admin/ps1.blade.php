@extends('layouts.sidebar')

@section('content')
    <h1 class="page-title">ps1 ファイル管理</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if (count($unused) === 0)
        <p>不要な ps1 ファイルはありません。</p>
    @else
        <form action="{{ route('admin.ps1.delete') }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="button" onclick="toggleAll()">すべて選択/解除</button>
            <button type="submit" onclick="return confirm('選択したファイルを削除しますか？');">削除</button>

            <ul style="margin-top: 15px;">
                @foreach ($unused as $file)
                    <li>
                        <label>
                            <input type="checkbox" name="files[]" value="{{ $file }}">
                            {{ basename($file) }}
                        </label>
                    </li>
                @endforeach
            </ul>
        </form>
    @endif

    <script>
        function toggleAll() {
            const boxes = document.querySelectorAll('input[type="checkbox"]');
            const allChecked = [...boxes].every(cb => cb.checked);
            boxes.forEach(cb => cb.checked = !allChecked);
        }
    </script>
@endsection
