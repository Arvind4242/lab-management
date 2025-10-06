<?php

namespace App\Filament\Admin\Resources\ReportResource\Pages;

use App\Filament\Admin\Resources\ReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function afterCreate(): void
    {
        $report = $this->record;
        $formData = $this->form->getState();
\Log::info('Form Data:', $formData);
\Log::info('Report Tests:', $report->report_tests->toArray());

        // Loop through the report tests that Filament already saved
        foreach ($report->report_tests as $index => $reportTest) {
            $testData = $formData['report_tests'][$index] ?? null;

            if (!empty($testData['results_temp'])) {
                foreach ($testData['results_temp'] as $result) {
                    $reportTest->results()->create([
                        'report_id' => $report->id,
                        'report_test_id' => $reportTest->id,
                        'test_name' => $reportTest->test_name,
                        'parameter_name' => $result['parameter_name'] ?? null,
                        'value' => $result['value'] ?? null,
                        'unit' => $result['unit'] ?? null,
                        'reference_range' => $result['reference_range'] ?? null,
                    ]);
                }
            }
        }
    }
}
