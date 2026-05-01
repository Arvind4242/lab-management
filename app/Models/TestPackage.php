<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPackage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'fee', 'gender', 'user_id'];

    public function scopeGlobal($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isOwnedBy($userId): bool
    {
        return $this->user_id === $userId;
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_package_test');
    }

    public function panels()
    {
        return $this->belongsToMany(TestPanel::class);
    }
}
