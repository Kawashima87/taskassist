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
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('action_type', ['program', 'popup'])->default('program')->after('screenshot_path'); // 操作種別
            $table->string('popup_title')->nullable()->after('action_type');   // ポップアップのタイトル
            $table->string('popup_message')->nullable()->after('popup_title'); // ポップアップの内容
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['action_type', 'popup_title', 'popup_message']);
        });
    }
};
