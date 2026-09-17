<?php

namespace App\Models;

use App\Enums\ResponseStatusOption;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'reponseId';

    protected $fillable = [
        'reportId',
        'solution',
        'photo',
        'responseDate',
        'status',
        'technicianId'
    ];

    protected function casts(): array {
        return [
            'photo' => 'json',
            'status' => ResponseStatusOption::class
        ];
    }

    public function report() {
        return $this->belongsTo(Report::class, 'reportId', 'reportId');
    }

    public function technician() {
        return $this->belongsTo(User::class, 'technicianId', 'userId');
    }
}
