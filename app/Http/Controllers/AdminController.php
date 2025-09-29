<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    // ① ユーザー管理
    public function users(Request $request) {
        $search = $request->input('search');
        $query = User::query();
        if ($search) {
            $query->where('name', 'like', "%$search%");
        }
        $users = $query->orderBy('id')->paginate(10);
        return view('admin.users', compact('users', 'search'));
    }

    public function deleteUser($id) {
        $user = User::findOrFail($id);
        if ($user->is_admin) {
            return back()->withErrors('管理者は削除できません');
        }

        // 投稿も一緒に削除
        $user->posts()->delete();
        $user->delete();

        return back()->with('success', 'ユーザーを削除しました');
    }


    public function userDetail($id) {
        $user = User::with(['posts', 'posts.favorites'])->findOrFail($id);
        $totalFavorites = $user->posts->sum(fn($post) => $post->favorites->count());
        return view('admin.user_detail', compact('user', 'totalFavorites'));
    }

    // ② エラーログ表示
    public function logs() {
        $logFile = storage_path('logs/laravel.log');
        $lines = [];
        if (file_exists($logFile)) {
            $lines = array_slice(file($logFile), -100); // 後ろ100行だけ
        }
        return view('admin.logs', ['lines' => $lines]);
    }

    // ③ ps1ファイル管理
    public function ps1List() {
        $files = glob(storage_path('app/*.ps1'));
        $used = Post::whereNotNull('ps1_path')->pluck('ps1_path')->toArray();
        $unused = array_diff($files, $used);
        return view('admin.ps1', compact('unused'));
    }

    public function deletePs1(Request $request) {
        $files = $request->input('files', []);
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        return back()->with('success', '不要ファイルを削除しました');
    }
}
