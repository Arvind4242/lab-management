<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestPanel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'hide_interpretation',
        'hide_method_instrument',
        'tests',
        'interpretation',
    ];

    protected $casts = [
        'hide_interpretation' => 'boolean',
        'hide_method_instrument' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(TestCategory::class);
    }

    public function testPackages()
{
    return $this->belongsToMany(TestPackage::class);
}


}
