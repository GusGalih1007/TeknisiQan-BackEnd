<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'compId';

    protected $fillable = [
        'name',
        'leaderId',
        'address',
        'logo',
        'ticket_sequence'
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leaderId', 'userId');
    }

    public function companyUnits()
    {
        return $this->hasMany(Unit::class, 'compId', 'compId');
    }

    public function companyRooms()
    {
        return $this->hasMany(Room::class, 'compId', 'compId');
    }
}
