<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_id')->nullable()->after('name');
            $table->string('market_id')->nullable()->after('market');
            $table->text('short_description_id')->nullable()->after('short_description');
            $table->longText('description_id')->nullable()->after('description');
            $table->text('image_url')->nullable()->after('image');
            $table->string('gallery_image')->nullable()->after('image_url');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_id')->nullable()->after('title');
            $table->string('category_id')->nullable()->after('category');
            $table->text('excerpt_id')->nullable()->after('excerpt');
            $table->longText('body_id')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('products', fn (Blueprint $table) => $table->dropColumn(['name_id', 'market_id', 'short_description_id', 'description_id', 'image_url', 'gallery_image']));
        Schema::table('posts', fn (Blueprint $table) => $table->dropColumn(['title_id', 'category_id', 'excerpt_id', 'body_id']));
    }
};
