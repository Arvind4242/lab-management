<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
    ];

    // Optional: Define relationships here if needed, e.g., subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
