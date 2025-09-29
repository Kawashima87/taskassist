@extends('layouts.sidebar')

@section('content')
    <h1 class="page-title">ログファイル</h1>

<h1 class="text-xl font-bold mb-4">エラーログ(最新100行)</h1>
    @if (empty($lines))
        <p>ログはまだありません</p>
    @else
        <pre style="background: #f4f4f4; border:1px solid #ccc; overflow:auto; white-space:pre-wrap;">@foreach ($lines as $line){{$line}}@endforeach</pre>
    @endif
@endsection