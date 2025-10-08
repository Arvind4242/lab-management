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
        'tests' => 'array',
    ];

     /**
     * Get all tests for this panel.
     *
     * @return array
     */
  // In TestPanel.php
public static function getTestsById($panelId)
{
    $panel = self::find($panelId);

    if (!$panel || empty($panel->tests)) {
        return [];
    }

    // Fetch tests with their unit
    return Test::with('unit')
        ->whereIn('id', $panel->tests)
        ->get()
        ->map(function($test) {
            return [
                'id' => $test->id,
                'name' => $test->name,
                'unit' => $test->unit ? $test->unit->name : '', // fetch unit name
                'reference_range' => $test->default_result ?? '',
            ];
        });
}


    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_panel_test', 'panel_id', 'test_id')
                    ->withPivot(['order']);
    }



    public function category()
    {
        return $this->belongsTo(TestCategory::class);
    }

    public function testPackages()
{
    return $this->belongsToMany(TestPackage::class);
}


}
