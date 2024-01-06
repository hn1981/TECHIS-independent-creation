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
        Schema::table('ramen_images', function (Blueprint $table) {
            // バイナリデータ保管用のカラム作成
            $table->longText('image')->after('ramen_id')->nullable();
            // image_pathをnullableに変更
            $table->text('image_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ramen_images', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->text('image_path')->nullable(false)->change();
        });
    }
};
