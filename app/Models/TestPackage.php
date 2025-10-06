<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fee',
        'gender',
    ];

    public function tests()
    {
        return $this->belongsToMany(Test::class);
    }

    public function panels()
    {
        return $this->belongsToMany(TestPanel::class);
    }
}
