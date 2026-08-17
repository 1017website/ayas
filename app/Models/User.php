<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    public const ROLE_HEAD_ADMIN = 'head_admin';

    public const ROLE_ADMIN_TEAM = 'admin_team';

    public const ROLE_CONTRIBUTOR = 'contributor';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isDeveloper(): bool
    {
        return strcasecmp($this->email, (string) config('ayas.developer_email')) === 0;
    }

    public static function roles(): array
    {
        return [
            self::ROLE_HEAD_ADMIN => 'Ketua Admin',
            self::ROLE_ADMIN_TEAM => 'Tim Admin',
            self::ROLE_CONTRIBUTOR => 'Kontributor',
        ];
    }

    public function roleLabel(): string
    {
        return self::roles()[$this->role] ?? 'Tanpa Role';
    }

    public function isHeadAdmin(): bool
    {
        return $this->role === self::ROLE_HEAD_ADMIN;
    }

    public function canEditWebsite(): bool
    {
        return in_array($this->role, [self::ROLE_HEAD_ADMIN, self::ROLE_ADMIN_TEAM], true);
    }

    public function canEditPosts(): bool
    {
        return in_array($this->role, array_keys(self::roles()), true);
    }
}
