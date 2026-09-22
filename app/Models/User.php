<?php

namespace App\Models;

use App\Enums\RoleOption;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasUuids;

    protected $table = 'users';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'userId';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'photo',
        'compId'
    ];

    protected function casts(): array
    {
        return [
            'userId' => 'string',
            'password' => 'hashed',
            'photo' => 'json',
            'role' => RoleOption::class,
        ];
    }

    public function company() {
        return $this->belongsTo(Company::class, 'compId', 'compId');
    }

    public function companyLeader() {
        return $this->hasOne(Company::class, 'leaderId', 'userId');
    }
}
