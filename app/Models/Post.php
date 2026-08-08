<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'title_id', 'slug', 'category', 'category_id', 'excerpt', 'excerpt_id',
        'body', 'body_id', 'image', 'gallery_image_2', 'gallery_image_3',
        'gallery_caption_1', 'gallery_caption_1_id', 'gallery_caption_2',
        'gallery_caption_2_id', 'gallery_caption_3', 'gallery_caption_3_id',
        'seo_title', 'seo_description', 'published_at', 'is_published',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_published' => 'boolean'];
    }
}
