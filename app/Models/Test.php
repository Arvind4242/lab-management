<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
     protected $fillable = [
        'name', 'short_name', 'category_id', 'unit_id', 'input_type',
        'default_result', 'optional', 'price', 'method', 'instrument', 'interpretation'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function category()
    {
        return $this->belongsTo(TestCategory::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function testPackages()
{
    return $this->belongsToMany(TestPackage::class);
}



}

