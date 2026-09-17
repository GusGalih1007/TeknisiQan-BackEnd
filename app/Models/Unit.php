<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'unitId';

    protected $fillable = [
        'unitNumber',
        'unitName',
        'compId',
        'roomId',
    ];

    public function company() {
        return $this->belongsTo(Company::class, 'compId', 'compId');
    }

    public function room() {
        return $this->belongsTo(User::class, 'roomId', 'roomId');
    }
}
