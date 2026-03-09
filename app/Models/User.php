<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_SUB_EDITOR = 'sub_editor';
    const ROLE_REPORTER = 'reporter';
    const ROLE_GUEST = 'guest';

    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_BANNED = 'banned';
    const STATUS_PENDING = 'pending';


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

    public function reporter()
    {
        return $this->hasOne(Reporter::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function isSuperAdmin() { return $this->role === self::ROLE_SUPER_ADMIN; }
    public function isEditor() { return $this->role === self::ROLE_EDITOR; }
    public function isSubEditor() { return $this->role === self::ROLE_SUB_EDITOR; }
    public function isReporter() { return $this->role === self::ROLE_REPORTER; }
    public function isGuest() { return $this->role === self::ROLE_GUEST; }
    
    public function canManageUsers() { return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_EDITOR]); }
    public function canApproveArticles() { return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_EDITOR, self::ROLE_SUB_EDITOR]); }
}

