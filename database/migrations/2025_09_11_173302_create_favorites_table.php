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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // お気に入りしたユーザー
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // お気に入り対象の投稿
            $table->timestamps();
            $table->unique(['user_id', 'post_id']); // 同じユーザーが同じ投稿を重複して保存できないように
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
