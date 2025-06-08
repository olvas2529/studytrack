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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // 主キー
            $table->unsignedBigInteger('user_id'); // ユーザーID（外部キー）
            $table->string('tasukumei'); // タスク名
            $table->text('shousai')->nullable(); // 詳細
            $table->string('kategori')->nullable(); // カテゴリ
            $table->integer('mokuhyou_jikan')->default(0); // 目標時間（分）
            $table->date('shimekiri')->nullable(); // 締切日
            $table->integer('shinchoku_ritsu')->default(0); // 進捗率
            $table->boolean('kanryou')->default(false); // 完了フラグ
            $table->tinyInteger('yuusendo')->default(2); // 優先度（1:高,2:中,3:低）
            $table->timestamps(); // touroku_bi / koushin_bi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
