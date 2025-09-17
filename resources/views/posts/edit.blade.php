<h2>タスク編集</h2>

<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label>タイトル:</label>
        <input type="text" name="title" value="{{ old('title', $post->title) }}" required>
    </div>

    <div>
        <label>説明:</label>
        <textarea name="body">{{ old('body', $post->body) }}</textarea>
    </div>

    <div>
        <label>実行ファイルのパス:</label>
        <input type="text" name="program_path" value="{{ old('program_path', $post->program_path) }}" required>
    </div>

    <div>
        <label>引数:</label>
        <input type="text" name="arguments" value="{{ old('arguments', $post->arguments) }}">
    </div>

    <div>
        <label>実行日時:</label>
        <input type="datetime-local" name="run_datetime"
                value="{{ old('run_datetime', \Carbon\Carbon::parse($post->run_datetime)->format('Y-m-d\TH:i')) }}">
    </div>

    <div>
        <label>スクリーンショット:</label>
        @if ($post->screenshot_path)
            <p>現在: <img src="{{ asset('storage/'.$post->screenshot_path) }}" alt="スクショ" style="width:100px;"></p>
        @endif
        <input type="file" name="screenshot">
    </div>

    <button type="submit">更新</button>
    <a href="{{ route('posts.show', $post->id) }}">キャンセル</a>
</form>