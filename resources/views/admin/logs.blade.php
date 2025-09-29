@extends('layouts.sidebar')

@section('content')
    <h1 class="page-title">ログファイル</h1>

    <h1 class="log-title">エラーログ(最新100行)</h1>
    @if (empty($lines))
        <p>ログはまだありません</p>
    @else
        <pre class="log-box">
@foreach ($lines as $line){{$line}}@endforeach
        </pre>
    @endif
@endsection
