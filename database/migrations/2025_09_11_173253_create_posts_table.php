<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 投稿者
            $table->string('title')->unique();          // タスク名 (schtasksで一意に利用)
            $table->text('body')->nullable();   // 説明文
            $table->string('program_path');            // 実行ファイルのパス
            $table->string('arguments')->nullable();   // 引数（不要ならNULL）
            $table->dateTime('run_datetime');          // 実行日時
            $table->boolean('enabled')->default(true); // 有効/無効（停止時にfalse）
            $table->string('screenshot_path')->nullable(); // 実行結果スクショの保存先パス
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
