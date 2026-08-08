<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone', 'product', 'message', 'status',
        'qontak_status', 'qontak_reference', 'qontak_synced_at', 'qontak_error',
    ];

    protected function casts(): array
    {
        return ['qontak_synced_at' => 'datetime'];
    }
}
