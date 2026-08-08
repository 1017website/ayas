<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QontakWebhookEvent extends Model
{
    protected $fillable = ['event_name', 'external_id', 'payload', 'received_at'];

    protected function casts(): array
    {
        return ['payload' => 'array', 'received_at' => 'datetime'];
    }
}
