<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'visitor_id', 'session_id', 'path', 'route_name', 'referrer', 'source',
        'medium', 'campaign', 'device', 'browser', 'ip_hash', 'viewed_at',
    ];

    protected function casts(): array
    {
        return ['viewed_at' => 'datetime'];
    }
}
