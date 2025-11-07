<?php

namespace App\Models;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
     protected $fillable = [
        'name', 'short_name', 'category_id', 'unit_id', 'input_type',
        'default_result','default_result_female', 'default_result_other', 'optional', 'price', 'method', 'instrument', 'interpretation'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

   public function category()
{
    return $this->belongsTo(TestCategory::class, 'category_id');
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

