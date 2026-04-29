<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RadhaRajawatReportSeeder extends Seeder
{
    public function run(): void
    {
        $reportId = DB::table('reports')->insertGetId([
            'user_id'       => 1,
            'patient_name'  => 'MRS. RADHA RAJAWAT',
            'age'           => 40,
            'gender'        => 'Female',
            'test_date'     => '2025-08-07',
            'referred_by'   => 'SELF',
            'client_name'   => 'PRATAP DIAGNOSTIC',
            'remarks'       => null,
            'test_panel_id' => null,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $panels = [
            [
                'name' => 'Haematology',
                'results' => [
                    // BLOOD COUNTS
                    [1,  'BLOOD COUNTS',           'Haemoglobin',               '10.0',         'g/dl',     '12-15'],
                    [2,  'BLOOD COUNTS',           'RBC Count',                 '3.53',         'mill/cumm','4.5-5.5'],
                    [3,  'BLOOD COUNTS',           'Total WBC count',           '12300',        '/Cumm',    '4000-10000'],
                    [4,  'BLOOD COUNTS',           'Platelets Count',           '542000',       '/cumm',    '150000-410000'],
                    // WBC DIFFERENTIAL COUNT
                    [5,  'WBC DIFFERENTIAL COUNT', 'Neutrophile',               '80',           '%',        '30-70'],
                    [6,  'WBC DIFFERENTIAL COUNT', 'Lymphocyte',                '16',           '%',        '20-40'],
                    [7,  'WBC DIFFERENTIAL COUNT', 'Eosinophil',                '02',           '%',        '1-6'],
                    [8,  'WBC DIFFERENTIAL COUNT', 'Monocyte',                  '02',           '%',        '2-10'],
                    // BLOOD INDICES
                    [9,  'BLOOD INDICES',          'PCV',                       '28.9',         '%',        '37.0 - 47.0'],
                    [10, 'BLOOD INDICES',          'MCV',                       '81.9',         'fL',       '83-101'],
                    [11, 'BLOOD INDICES',          'MCH',                       '28.3',         'pg',       '27-32'],
                    [12, 'BLOOD INDICES',          'MCHC',                      '34.6',         'gm/dL',    '31.5-33.1'],
                    [13, 'BLOOD INDICES',          'RDW',                       '18.0',         '%',        '13-15'],
                    [14, 'BLOOD INDICES',          'MPV',                       '10.3',         'fL',       '7.2-11.7'],
                    [15, 'BLOOD INDICES',          'PDW',                       '16.3',         'fL',       '10-14'],
                    [16, 'BLOOD INDICES',          'PCT',                       '0.659',        '%',        '0.17-0.37'],
                    // ABSOLUTE COUNT
                    [17, 'ABSOLUTE COUNT',         'Absolute Neutrophil Count', '9840',         '/cumm',    '2000-7000'],
                    [18, 'ABSOLUTE COUNT',         'Absolute Lymphocyte Count', '1968',         '/cumm',    '1000-3000'],
                    [19, 'ABSOLUTE COUNT',         'Absolute Eosinophil Count', '246',          '/cumm',    '20-500'],
                    [20, 'ABSOLUTE COUNT',         'Absolute Monocyte Count',   '246',          '/cumm',    '200-1000'],
                ],
            ],
            [
                'name' => 'ERYTHROCYTE SEDIMENTATION RATE',
                'results' => [
                    [1, 'ERYTHROCYTE SEDIMENTATION RATE', 'Erythrocyte Sedimentation Rate', '35', 'mm/hr', '<50 years: < 15 mm/hr, >50 years: < 20 mm/hr'],
                ],
            ],
            [
                'name' => 'HbA1c (Glycated Haemoglobin)',
                'results' => [
                    [1, 'HbA1c', 'HbA1c (Glycocylated Haemoglobin)', '5.8',  '%',    'Below 6.0: Normal, 6.0-7.0: Good, 7.0-8.0: Fair, 8.0-10.0: Unsatisfactory, >10: Poor'],
                    [2, 'HbA1c', 'Mean Blood Glucose',                '119.8','mg/dL','90-120: Excellent, 121-150: Good, 151-181: Average, 181-210: Action Suggested, >211: Panic'],
                ],
            ],
            [
                'name' => 'RENAL FUNCTION TEST',
                'results' => [
                    [1, 'RENAL FUNCTION TEST', 'Blood Urea Nitrogen',  '11.48', 'mg/dL',  '7 - 20'],
                    [2, 'RENAL FUNCTION TEST', 'Urea',                 '24.6',  'mg/dL',  '15.0 - 45.0'],
                    [3, 'RENAL FUNCTION TEST', 'Creatinine',           '0.98',  'mg/dL',  '0.7-1.3'],
                    [4, 'RENAL FUNCTION TEST', 'Uric Acid',            '2.60',  'mg/dL',  '2.5-7.5'],
                    [5, 'RENAL FUNCTION TEST', 'S. Calcium',           '9.20',  'mg/dL',  '8.6-10.0'],
                    [6, 'RENAL FUNCTION TEST', 'BUN Creatinine Ratio', '11.71', 'mg/dL',  '5 - 20'],
                    [7, 'RENAL FUNCTION TEST', 'Sodium (Na+)',         '138.4', 'mmol/L', '135 - 155'],
                    [8, 'RENAL FUNCTION TEST', 'Potassium(K+)',        '4.08',  'mmol/L', '3.5 - 5.5'],
                    [9, 'RENAL FUNCTION TEST', 'Chloride (Cl-)',       '101.5', 'mmol/L', '98 - 108'],
                ],
            ],
            [
                'name' => 'Biochemistry',
                'results' => [
                    [1, 'BLOOD GLUCOSE', 'Fasting Blood Sugar', '87.4', 'mg/dL', '65-110'],
                ],
            ],
            [
                'name' => 'Lipid Profile',
                'results' => [
                    [1, 'Lipid Profile', 'Total Cholesterol',          '181.0', 'mg/dL', 'Normal: <200, Borderline: 200-239, High: >239'],
                    [2, 'Lipid Profile', 'Triglyceride',               '112.0', 'mg/dL', 'Normal: <161, High: 161-199, Very High: >500'],
                    [3, 'Lipid Profile', 'HDL-Cholesterol, Direct',    '53.6',  'mg/dL', '35.3-79.5'],
                    [4, 'Lipid Profile', 'LDL-Cholesterol, Calculated','105.0', 'mg/dL', 'Optimal: <100, Near Optimal: 100-129, Borderline: 130-159, High: 160-189, Very High: >190'],
                    [5, 'Lipid Profile', 'VLDL Cholesterol',           '22.4',  'mg/dL', ''],
                    [6, 'Lipid Profile', 'Total Cholesterol/HDL Ratio','3.4',   'Ratio', '3.5 - 5.0'],
                    [7, 'Lipid Profile', 'LDL Chol./HDL Chol. RATIO', '1.96',  'Ratio', '1.5 - 3.0'],
                ],
            ],
            [
                'name' => 'LIVER FUNCTION TEST',
                'results' => [
                    [1,  'LIVER FUNCTION TEST', 'Total Bilirubin',       '1.10',  'mg/dL', '0.2-1.28'],
                    [2,  'LIVER FUNCTION TEST', 'Direct Bilirubin',      '0.25',  'mg/dL', '0.1-0.25'],
                    [3,  'LIVER FUNCTION TEST', 'Indirect Bilirubin',    '0.85',  'mg/dL', '0.0-1.0'],
                    [4,  'LIVER FUNCTION TEST', 'SGOT(AST)',             '22.2',  'U/L',   '0.0 - 46.0'],
                    [5,  'LIVER FUNCTION TEST', 'SGPT(ALT)',             '20.2',  'U/L',   '0.0 - 49.0'],
                    [6,  'LIVER FUNCTION TEST', 'Alkaline Phosphatase',  '98.1',  'IU/L',  '53 - 128'],
                    [7,  'LIVER FUNCTION TEST', 'Total Protein',         '7.70',  'g/dl',  '6.0-8.0'],
                    [8,  'LIVER FUNCTION TEST', 'Albumin',               '4.60',  'gm/dL', '3.5 - 5.2'],
                    [9,  'LIVER FUNCTION TEST', 'Globulin',              '3.10',  'g/dL',  '1.8-3.6'],
                    [10, 'LIVER FUNCTION TEST', 'A/G Ratio',             '1.48',  'Ratio', '0.9 - 2.0'],
                ],
            ],
            [
                'name' => 'URINE EXAMINATION',
                'results' => [
                    // PHYSICAL
                    [1,  'PHYSICAL EXAMINATION',  'Quantity',              '15 ml',           'ML',   ''],
                    [2,  'PHYSICAL EXAMINATION',  'Colour',                'Pale Yellow',      '',     ''],
                    [3,  'PHYSICAL EXAMINATION',  'Appearance',            'Turbid',           '',     'Clear'],
                    [4,  'PHYSICAL EXAMINATION',  'Reaction (pH)',         '6.0',              '',     '4.6 - 7.5'],
                    [5,  'PHYSICAL EXAMINATION',  'Specific Gravity',      '1.015',            '',     '1.005 - 1.030'],
                    [6,  'PHYSICAL EXAMINATION',  'Urobilinogen',          'Absent',           '',     'Absent'],
                    // CHEMICAL
                    [7,  'CHEMICAL EXAMINATION',  'Protein',               'Present (++)',     '',     'Absent'],
                    [8,  'CHEMICAL EXAMINATION',  'Sugar',                 'Absent',           '',     'Absent'],
                    [9,  'CHEMICAL EXAMINATION',  'Blood',                 'Absent',           '',     ''],
                    [10, 'CHEMICAL EXAMINATION',  'Ketone',                'Absent',           '',     'Absent'],
                    [11, 'CHEMICAL EXAMINATION',  'Bile Salt',             'Absent',           '',     'Absent'],
                    [12, 'CHEMICAL EXAMINATION',  'Bile Pigment',          'Absent',           '',     'Absent'],
                    // MICROSCOPIC
                    [13, 'MICROSCOPIC EXAMINATION','Pus Cells',            'Plenty /hpf',      '/HPF', ''],
                    [14, 'MICROSCOPIC EXAMINATION','Red Blood Cells (RBCs)','Absent',          '/HPF', ''],
                    [15, 'MICROSCOPIC EXAMINATION','Epithelial Cells',     '8 - 10 /hpf',      '',     ''],
                    [16, 'MICROSCOPIC EXAMINATION','Casts',                'Granular Cast (+)','',     'Absent'],
                    [17, 'MICROSCOPIC EXAMINATION','Crystals',             'Absent',           '',     'Absent'],
                    [18, 'MICROSCOPIC EXAMINATION','Bacteria',             'Present (++)',      '',     'Absent'],
                ],
            ],
            [
                'name' => 'THYROID FUNCTION TEST',
                'results' => [
                    [1, 'THYROID FUNCTION TEST', 'Total T3 (Triiodothyronine)',       '163.0',  'ng/dL',  '60 - 181'],
                    [2, 'THYROID FUNCTION TEST', 'Total T4 (Thyroxine)',              '5.31',   'ug/dl',  '5.48 - 14.28'],
                    [3, 'THYROID FUNCTION TEST', 'TSH Ultra (Thyroid Stimulating Hormone)', '60.400', 'ulU/ml', '0.350 - 5.500'],
                ],
            ],
        ];

        foreach ($panels as $panel) {
            $reportTestId = DB::table('report_tests')->insertGetId([
                'report_id'    => $reportId,
                'test_id'      => null,
                'test_panel_id'=> null,
                'test_name'    => $panel['name'],
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            foreach ($panel['results'] as [$order, $testName, $paramName, $value, $unit, $refRange]) {
                DB::table('report_results')->insert([
                    'display_order'   => $order,
                    'report_id'       => $reportId,
                    'report_test_id'  => $reportTestId,
                    'test_name'       => $testName,
                    'parameter_name'  => $paramName,
                    'value'           => $value,
                    'unit'            => $unit,
                    'reference_range' => $refRange,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }
}
