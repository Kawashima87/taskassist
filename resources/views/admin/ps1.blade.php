@extends('layouts.sidebar')

@section('content')
    <h1 class="page-title">ps1 ファイル管理</h1>

    @if (count($unused) === 0)
        <p class="no-file-message">不要な ps1 ファイルはありません。</p>
    @else
        <form action="{{ route('admin.ps1.delete') }}" method="POST" class="ps1-form">
            @csrf
            @method('DELETE')

            <div class="ps1-actions">
                <button type="button" class="toggle-btn" onclick="toggleAll()">すべて選択/解除</button>
                <button type="submit" class="delete-btn" onclick="return confirm('選択したファイルを削除しますか？');">削除</button>
            </div>

            <table class="ps1-table">
                <thead>
                    <tr>
                        <th>選択</th>
                        <th>ファイル名</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($unused as $file)
                        <tr>
                            <td><input type="checkbox" name="files[]" value="{{ $file }}"></td>
                            <td>{{ basename($file) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
