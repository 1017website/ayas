<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function values(): array
    {
        return static::query()->pluck('value', 'key')->all();
    }

    public static function secret(string $key): ?string
    {
        $value = static::query()->where('key', $key)->value('value');
        if (! $value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return null;
        }
    }
}
