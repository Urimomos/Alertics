<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_name',
        'phone',
        'emergency_type_id',
        'description',
        'latitude',
        'longitude',
        'status', // Recuerda que por defecto es 'pending'
    ];

    // Relación para saber qué tipo de emergencia es
    public function emergencyType(): BelongsTo
    {
        return $this->belongsTo(EmergencyType::class);
    }
}