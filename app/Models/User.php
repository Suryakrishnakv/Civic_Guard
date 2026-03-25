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

    const ROLE_CITIZEN = 'citizen';
    const ROLE_OFFICER = 'officer';
    const ROLE_ADMIN = 'admin';

    const DEPT_HEALTH = 'Health & Sanitation Department';
    const DEPT_ENGINEERING = 'Engineering & Public Works Department';
    const DEPT_WASTE = 'Solid Waste Management Department';
    const DEPT_ELECTRICITY = 'Electrical & Street Lighting Department';
    const DEPT_WATER = 'Water Supply & Sewerage Department';
    const DEPT_PLANNING = 'Town Planning & Building Department';

    public static function getDepartments()
    {
        return [
            self::DEPT_HEALTH,
            self::DEPT_ENGINEERING,
            self::DEPT_WASTE,
            self::DEPT_ELECTRICITY,
            self::DEPT_WATER,
            self::DEPT_PLANNING,
        ];
    }

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
        'department',
        'is_subscribed',
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
            'is_subscribed' => 'boolean',
        ];
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function isOfficer()
    {
        return $this->role === self::ROLE_OFFICER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
