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
        Schema::create('ramen_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ramen_id')->unsigned()->index();
            $table->text('image_path');
            $table->timestamps();

            //外部キー制約
            $table->foreign('ramen_id')->references('id')->on('ramens')->onDelete('cascade'); //該当するラーメンが削除された場合、それに紐づく画像も自動的に削除されるよう設定
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramen_images');
    }
};
