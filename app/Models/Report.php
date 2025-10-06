<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
     protected $fillable = [
        'patient_name','age','gender','test_date','remarks','referred_by', 'client_name'
    ];

    // public function report_tests() {
    //     return $this->hasMany(ReportTest::class);
    // }

    public function results() {
    return $this->hasMany(ReportResult::class, 'report_id');
}

// public function report_tests() {
//     return $this->hasMany(ReportTest::class, 'report_id');
// }

public function report_tests() {
    return $this->hasMany(ReportTest::class);
}

      public function panel()
    {
        return $this->belongsTo(TestPanel::class, 'test_panel_id');
    }

    // public function report_tests() {
    //     return $this->hasMany(ReportTest::class);
    // }

    // public function results()
    // {
    //     return $this->hasMany(ReportResult::class);
    // }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // public function test()
    // {
    //     return $this->belongsTo(Test::class);
    // }
}

