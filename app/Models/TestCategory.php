<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name'];

    public function tests()
    {
        return $this->hasMany(Test::class, 'category_id');
    }
}
