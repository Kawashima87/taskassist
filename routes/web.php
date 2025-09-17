<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FavoriteController;

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

Route::resource('posts', PostController::class); //posts 以下に index/create/store/show/edit/update/destroy 自動割り当て
Route::get('/', [PostController::class, 'index'])->name('posts.index')->middleware('auth');//デフォルト画面
Route::get('/favorites', [PostController::class, 'favorites'])->name('posts.favorites')->middleware('auth');// 自分のお気に入り一覧
Route::get('/myposts', [PostController::class, 'myposts'])->name('posts.myposts')->middleware('auth');// 自分の投稿一覧

Route::post('/posts/{post}/favorite', [FavoriteController::class, 'store'])->name('favorites.store')->middleware('auth');//お気に入り登録
Route::delete('/posts/{post}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy')->middleware('auth');//お気に入り解除