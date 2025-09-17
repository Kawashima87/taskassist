<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <button type="button">☆</button>
    画像 <input type="file" name="screenshot">
    タイトル<input type="text" name="title">
    説明<input type="text" name="body">
    パス<input type="text" name="program_path">
    引数<input type="text" name="arguments">
    実行日時<input type="datetime-local" name="run_datetime">

     <input type="hidden" name="enabled" value="1">

    <input type="submit" value="キャンセル">
    <input type="submit" value="実行">
</form>