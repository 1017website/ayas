<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_id')->index();
            $table->string('session_id', 120)->nullable()->index();
            $table->string('path', 500)->index();
            $table->string('route_name')->nullable();
            $table->text('referrer')->nullable();
            $table->string('source')->default('Direct');
            $table->string('medium')->nullable();
            $table->string('campaign')->nullable();
            $table->string('device', 30)->default('Desktop');
            $table->string('browser', 50)->nullable();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->timestamp('viewed_at')->index();
            $table->timestamps();
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('qontak_status')->default('not_configured')->after('status');
            $table->string('qontak_reference')->nullable()->after('qontak_status');
            $table->timestamp('qontak_synced_at')->nullable()->after('qontak_reference');
            $table->text('qontak_error')->nullable()->after('qontak_synced_at');
        });

        Schema::create('qontak_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->nullable()->index();
            $table->string('external_id')->nullable()->index();
            $table->json('payload');
            $table->timestamp('received_at')->index();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('gallery_image_2')->nullable()->after('image');
            $table->string('gallery_image_3')->nullable()->after('gallery_image_2');
            $table->string('gallery_caption_1')->nullable()->after('gallery_image_3');
            $table->string('gallery_caption_1_id')->nullable()->after('gallery_caption_1');
            $table->string('gallery_caption_2')->nullable()->after('gallery_caption_1_id');
            $table->string('gallery_caption_2_id')->nullable()->after('gallery_caption_2');
            $table->string('gallery_caption_3')->nullable()->after('gallery_caption_2_id');
            $table->string('gallery_caption_3_id')->nullable()->after('gallery_caption_3');
            $table->string('seo_title')->nullable()->after('gallery_caption_3_id');
            $table->text('seo_description')->nullable()->after('seo_title');
        });
    }

    public function down(): void
    {
        Schema::table('posts', fn (Blueprint $table) => $table->dropColumn([
            'gallery_image_2', 'gallery_image_3',
            'gallery_caption_1', 'gallery_caption_1_id',
            'gallery_caption_2', 'gallery_caption_2_id',
            'gallery_caption_3', 'gallery_caption_3_id',
            'seo_title', 'seo_description',
        ]));

        Schema::dropIfExists('qontak_webhook_events');

        Schema::table('inquiries', fn (Blueprint $table) => $table->dropColumn([
            'qontak_status', 'qontak_reference', 'qontak_synced_at', 'qontak_error',
        ]));

        Schema::dropIfExists('page_views');
    }
};
