<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = "roomId";

    protected $fillable = [
        'roomName',
        'compId',
    ];

    public function roomUnits() {
        return $this->hasMany(Unit::class, 'roomId', 'roomId');
    }

    public function company() {
        return $this->belongsTo(Unit::class, 'compId', 'compId');
    }
}
