<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('study_logs', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->unsignedBigInteger('user_id'); // ユーザーID
            $table->unsignedBigInteger('task_id'); // タスクID
            $table->date('gakushuu_bi'); // 学習日
            $table->integer('gakushuu_jikan'); // 学習時間（分）
            $table->text('memo')->nullable(); // メモ
            $table->timestamps(); // touroku_bi / koushin_bi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_logs');
    }
};
