<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = "string";
    protected $primaryKey = 'reportId';

    protected $fillable = [
        'ticketNumber',
        'unitId',
        'problem',
        'reportBy',
        'compId',
        'reportDate',
        'photo'
    ];

    protected function casts(): array {
        return [
            'photo' => 'json',
            'reportDate' => 'datetime',
        ];
    }
}
