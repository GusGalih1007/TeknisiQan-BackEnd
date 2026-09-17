<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleOption;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'userid';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'photo',
        'compId'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'photo' => 'json',
            'role' => RoleOption::class,
        ];
    }

    public function company() {
        return $this->belongsTo(Company::class, 'compId', 'compId');
    }

    public function companyLeader() {
        return $this->hasMany(Company::class, 'leaderId', 'userId');
    }
}
