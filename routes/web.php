<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//!タイムスケジューラ

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.users.index');

    // ① ユーザー管理
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::get('/users/{id}', [AdminController::class, 'userDetail'])->name('admin.users.detail');

    // ② エラーログ表示
    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');

    // ③ ps1ファイル管理
    Route::get('/ps1', [AdminController::class, 'ps1List'])->name('admin.ps1');
    Route::delete('/ps1', [AdminController::class, 'deletePs1'])->name('admin.ps1.delete');
});

Route::resource('posts', PostController::class); //posts 以下に index/create/store/show/edit/update/destroy 自動割り当て
Route::get('/', [PostController::class, 'index'])->name('posts.index')->middleware('auth');//デフォルト画面
Route::get('/mypage', [PostController::class, 'mypage'])->name('posts.mypage')->middleware('auth');

Route::post('/posts/{post}/favorite', [FavoriteController::class, 'store'])->name('favorites.store')->middleware('auth');//お気に入り登録
Route::delete('/posts/{post}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy')->middleware('auth');//お気に入り解除