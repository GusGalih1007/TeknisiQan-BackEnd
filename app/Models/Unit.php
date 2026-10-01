<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'qrCode',
        'photo',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'compId', 'compId');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'roomId', 'roomId');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'unitId', 'unitId');
    }
}
