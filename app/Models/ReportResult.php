<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportResult extends Model
{
    //  use HasFactory;

   protected $fillable = [
        'report_id', 'report_test_id', 'test_name', 'parameter_name', 'value', 'unit', 'reference_range'
    ];

    public function report() {
    return $this->belongsTo(Report::class);
}

public function report_test() {
    return $this->belongsTo(ReportTest::class);
}



    // public function report() {
    //     return $this->belongsTo(Report::class);
    // }


    // public function report_test() {
    //     return $this->belongsTo(ReportTest::class);
    // }


    public function panel()
    {
        return $this->belongsTo(TestPanel::class, 'test_panel_id');
    }



    public function parameter()
    {
        return $this->belongsTo(Test::class, 'test_parameter_id');
    }
}
