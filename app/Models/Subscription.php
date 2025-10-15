<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'plan',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationship to Lab
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
