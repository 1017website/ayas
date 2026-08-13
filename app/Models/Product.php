<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'name_id', 'slug', 'market', 'market_id', 'short_description', 'short_description_id', 'description', 'description_id', 'image', 'image_url', 'gallery_image', 'gallery_image_3', 'gallery_image_4', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
