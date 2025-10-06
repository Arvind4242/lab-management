<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTest extends Model
{
      protected $fillable = ['report_id','test_id','test_panel_id','test_name'];

      public function report() {
    return $this->belongsTo(Report::class);
}



public function results() {
    return $this->hasMany(ReportResult::class, 'report_test_id');
}

public function test() {
    return $this->belongsTo(Test::class);
}

public function panel() {
    return $this->belongsTo(TestPanel::class, 'test_panel_id');
}


    // public function report() {
    //     return $this->belongsTo(Report::class);
    // }

    // public function results() {
    //     return $this->hasMany(ReportResult::class);
    // }

    // public function test() {
    //     return $this->belongsTo(Test::class);
    // }


    // public function report() {
    //     return $this->belongsTo(Report::class);
    // }

    // public function results() {
    //     return $this->hasMany(ReportResult::class);
    // }

    // public function test() {
    //     return $this->belongsTo(Test::class);
    // }

     public function reportTest() {
        return $this->belongsTo(ReportTest::class);
    }
}
