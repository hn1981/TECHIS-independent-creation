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
        // MIMEタイプカラム作成
        $table->string('mime_type')->after('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ramen_images', function (Blueprint $table) {
            $table->dropColumn('mime_type');
        });
    }
};
