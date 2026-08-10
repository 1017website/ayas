<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    private const DEVELOPER_EMAIL = '1017website@gmail.com';

    public function up(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => self::DEVELOPER_EMAIL],
            [
                'name' => '1017 Website Developer',
                'password' => Hash::make('1017Website2020.'),
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }

    public function down(): void
    {
        DB::table('users')->where('email', self::DEVELOPER_EMAIL)->delete();
    }
};
