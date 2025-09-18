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
            // 既存 program_path を一度削除
            $table->dropColumn('program_path');
        });

        Schema::table('posts', function (Blueprint $table) {
            // nullable で再作成
            $table->string('program_path')->nullable()->after('body');
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
            // rollback 時は NOT NULL で戻す
            $table->dropColumn('program_path');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('program_path')->after('body');
        });
    }
};
