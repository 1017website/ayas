<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('gallery_image_3')->nullable()->after('gallery_image');
            $table->string('gallery_image_4')->nullable()->after('gallery_image_3');
        });
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['gallery_image_3', 'gallery_image_4']));
    }
};
