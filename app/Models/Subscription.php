<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id', 'user_id', 'plan_id', 'plan',
        'start_date', 'end_date', 'is_active',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'billing_cycle', 'price', 'payment_mode',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class);
    }

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
