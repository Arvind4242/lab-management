<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Sets up master data for the HEALTH GUARD GOLD package:
 * units → tests → test_panels → test_package
 *
 * Safe to run multiple times (checks before inserting).
 */
class HealthGuardGoldSetupSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. UNITS ──────────────────────────────────────────────────────────
        $ratioId = $this->unitId('Ratio');
        $dashId  = $this->unitId('-');       // for qualitative / no-unit tests

        // existing unit IDs (from production DB)
        $u = [
            'gdl'       => 1,   // g/dl
            'pct'       => 2,   // %
            'cumm'      => 4,   // cumm
            'fl'        => 5,   // fL
            'gmdl'      => 8,   // gm/dl
            'hpf'       => 12,  // /HPF
            'iuL'       => 13,  // IU/L
            'mgdl'      => 22,  // mg/dL
            'millcumm'  => 27,  // million/cumm
            'ml'        => 30,  // ml
            'mmhr'      => 32,  // mm for 1st hour
            'mmolL'     => 35,  // mmol/L
            'pg'        => 43,  // Pg
            'uL'        => 53,  // U/L
            'ratio'     => $ratioId,
            'dash'      => $dashId,
        ];

        // ── 2. TESTS ─────────────────────────────────────────────────────────
        // CBC absolute counts (Haematology = 1)
        $absNeutro = $this->testId('Absolute Neutrophil Count', 'ANC',  1, $u['cumm'],  'Numeric',    '2000-7000');
        $absLympho = $this->testId('Absolute Lymphocyte Count', 'ALC',  1, $u['cumm'],  'Numeric',    '1000-3000');
        $absEosino = $this->testId('Absolute Eosinophil Count', 'AEC2', 1, $u['cumm'],  'Numeric',    '20-500');
        $absMono   = $this->testId('Absolute Monocyte Count',   'AMC',  1, $u['cumm'],  'Numeric',    '200-1000');

        // HbA1c (Biochemistry = 2)
        $hba1c   = $this->testId('HbA1c (Glycocylated Haemoglobin)', 'HbA1c', 2, $u['pct'],  'Numeric',
            'Below 6.0: Normal, 6.0-7.0: Good Control, 7.0-8.0: Fair Control, 8.0-10.0: Unsatisfactory, Above 10: Poor Control');
        $meanBG  = $this->testId('Mean Blood Glucose', null, 2, $u['mgdl'], 'Numeric',
            '90 - 120: Excellent Control, 121 - 150: Good Control, 151 - 181: Average Control, 181 - 210: Action Suggested, Above 211: Panic Value');

        // Renal Function Test
        $bun       = $this->testId('Blood Urea Nitrogen',  'BUN',  2, $u['mgdl'],  'Numeric', '7 - 20');
        $urea      = $this->testId('Urea',                  null,   2, $u['mgdl'],  'Numeric', '15.0 - 45.0');
        $creat     = $this->testId('Creatinine',            null,   2, $u['mgdl'],  'Numeric', '0.7-1.3');
        $uricAcid  = $this->testId('Uric Acid',             null,   2, $u['mgdl'],  'Numeric', '2.5-7.5');
        $calcium   = $this->testId('S. Calcium',            null,   2, $u['mgdl'],  'Numeric', '8.6-10.0');
        $bunCr     = $this->testId('BUN Creatinine Ratio',  null,   2, $u['mgdl'],  'Numeric', '5 - 20');
        $sodium    = $this->testId('Sodium (Na+)',           null,   2, $u['mmolL'], 'Numeric', '135 - 155');
        $potassium = $this->testId('Potassium(K+)',          null,   2, $u['mmolL'], 'Numeric', '3.5 - 5.5');
        $chloride  = $this->testId('Chloride (Cl-)',         null,   2, $u['mmolL'], 'Numeric', '98 - 108');

        // Blood Glucose
        $fbs = $this->testId('Fasting Blood Sugar', 'FBS', 2, $u['mgdl'], 'Numeric', '65-110');

        // Lipid Profile
        $totalChol    = $this->testId('Total Cholesterol',           null,   2, $u['mgdl'],  'Numeric',
            'Normal: <200, Borderline High: 200-239, High: >239');
        $trig         = $this->testId('Triglyceride',                null,   2, $u['mgdl'],  'Numeric',
            'Normal: <161, High: 161-199, Very High: >500');
        $hdl          = $this->testId('HDL-Cholesterol, Direct',     'HDL',  2, $u['mgdl'],  'Numeric', '35.3-79.5');
        $ldl          = $this->testId('LDL-Cholesterol, Calculated', 'LDL',  2, $u['mgdl'],  'Numeric',
            'Optimal: <100, Near Optimal: 100-129, Borderline: 130-159, High: 160-189, Very High: >190');
        $vldl         = $this->testId('VLDL Cholesterol',            'VLDL', 2, $u['mgdl'],  'Numeric', null);
        $cholHdl      = $this->testId('Total Cholesterol/HDL Ratio', null,   2, $u['ratio'],  'Numeric', '3.5 - 5.0');
        $ldlHdl       = $this->testId('LDL Chol./HDL Chol. RATIO',  null,   2, $u['ratio'],  'Numeric', '1.5 - 3.0');

        // Liver Function Test
        $totalBili   = $this->testId('Total Bilirubin',      null,   2, $u['mgdl'], 'Numeric', '0.2-1.28');
        $directBili  = $this->testId('Direct Bilirubin',     null,   2, $u['mgdl'], 'Numeric', '0.1-0.25');
        $indirBili   = $this->testId('Indirect Bilirubin',   null,   2, $u['mgdl'], 'Numeric', '0.0-1.0');
        $sgot        = $this->testId('SGOT(AST)',             'AST',  2, $u['uL'],   'Numeric', '0.0 - 46.0');
        $sgpt        = $this->testId('SGPT(ALT)',             'ALT',  2, $u['uL'],   'Numeric', '0.0 - 49.0');
        $alp         = $this->testId('Alkaline Phosphatase', 'ALP',  2, $u['iuL'],  'Numeric', '53 -128');
        $totalProt   = $this->testId('Total Protein',         null,   2, $u['gdl'],  'Numeric', '6.0-8.0');
        $albumin     = $this->testId('Albumin',               null,   2, $u['gmdl'], 'Numeric', '3.5 - 5.2');
        $globulin    = $this->testId('Globulin',              null,   2, $u['gdl'],  'Numeric', '1.8-3.6');
        $agRatio     = $this->testId('A/G Ratio',             null,   2, $u['ratio'],'Numeric', '0.9 - 2.0');

        // Urine Examination (Clinical Pathology = 4)
        $urQty    = $this->testId('Quantity',              null, 4, $u['ml'],   'Single Line', null);
        $urColour = $this->testId('Colour',                null, 4, $u['dash'], 'Single Line', null);
        $urApp    = $this->testId('Appearance',            null, 4, $u['dash'], 'Single Line', 'Clear');
        $urPH     = $this->testId('Reaction (pH)',         null, 4, $u['dash'], 'Numeric',     '4.6 - 7.5');
        $urSG     = $this->testId('Specific Gravity',      null, 4, $u['dash'], 'Numeric',     '1.005 - 1.030');
        $urUrobi  = $this->testId('Urobilinogen',          null, 4, $u['dash'], 'Single Line', 'Absent');
        $urProt   = $this->testId('Protein (Urine)',       null, 4, $u['dash'], 'Single Line', 'Absent');
        $urSugar  = $this->testId('Sugar (Urine)',         null, 4, $u['dash'], 'Single Line', 'Absent');
        $urBlood  = $this->testId('Blood (Urine)',         null, 4, $u['dash'], 'Single Line', 'Absent');
        $urKetone = $this->testId('Ketone',                null, 4, $u['dash'], 'Single Line', 'Absent');
        $urBileS  = $this->testId('Bile Salt',             null, 4, $u['dash'], 'Single Line', 'Absent');
        $urBileP  = $this->testId('Bile Pigment',          null, 4, $u['dash'], 'Single Line', 'Absent');
        $urPus    = $this->testId('Pus Cells',             null, 4, $u['hpf'],  'Single Line', null);
        $urRBC    = $this->testId('Red Blood Cells (RBCs)',null, 4, $u['hpf'],  'Single Line', 'Absent');
        $urEpi    = $this->testId('Epithelial Cells',      null, 4, $u['hpf'],  'Single Line', null);
        $urCasts  = $this->testId('Casts',                 null, 4, $u['dash'], 'Single Line', 'Absent');
        $urCryst  = $this->testId('Crystals',              null, 4, $u['dash'], 'Single Line', 'Absent');
        $urBact   = $this->testId('Bacteria',              null, 4, $u['dash'], 'Single Line', 'Absent');

        // ── 3. UPDATE CBC PANEL (id=3) — add RDW,MPV,PDW,PCT + absolute counts ─
        $cbcTestIds = [3,28,4,14,15,16,17,18,19,20,21,22,23,24,25,27,9,7,
            $absNeutro,$absLympho,$absEosino,$absMono];
        DB::table('test_panels')->where('id', 3)
            ->update(['tests' => json_encode(array_map('strval', $cbcTestIds)), 'updated_at' => now()]);

        // ── 4. TEST PANELS ────────────────────────────────────────────────────
        $esrPanel = $this->panelId(
            'ERYTHROCYTE SEDIMENTATION RATE', 1, null, [7],
            "An erythrocyte sedimentation rate test measures the speed at which red blood cells settle to the bottom of an upright glass test tube.\n\nComment: Please correlate with clinical condition.\nNotes: Clinical diagnosis should not be made on the findings of a single test result, but should integrate both clinical and laboratory data."
        );

        $hba1cPanel = $this->panelId(
            'HbA1c (Glycated Haemoglobin)', 2, null, [$hba1c, $meanBG], null
        );

        $rftPanel = $this->panelId(
            'RENAL FUNCTION TEST', 2, null,
            [$bun,$urea,$creat,$uricAcid,$calcium,$bunCr,$sodium,$potassium,$chloride], null
        );

        $biochemPanel = $this->panelId(
            'Biochemistry', 2, null, [$fbs], null
        );

        $lipidPanel = $this->panelId(
            'Lipid Profile', 2, null,
            [$totalChol,$trig,$hdl,$ldl,$vldl,$cholHdl,$ldlHdl],
            "Please Note: Triglyceride level changes acutely & temporarily because of previous night's diet, fasting/non-fasting state & changes in the blood sugar level."
        );

        $lftInterpretation = "A liver function test (LFT) may be used to screen for liver damage.\n1. Bilirubin - increased when too much is produced or less removed.\n2. AST - very high with acute hepatitis, may be normal/moderate with chronic.\n3. ALT - very high with acute hepatitis, moderate with chronic.\n4. ALP - significantly increased with obstructed bile ducts, cirrhosis, liver cancer.\n5. Protein - typically normal with liver disease.";
        $lftPanel = $this->panelId(
            'LIVER FUNCTION TEST', 2, null,
            [$totalBili,$directBili,$indirBili,$sgot,$sgpt,$alp,$totalProt,$albumin,$globulin,$agRatio],
            $lftInterpretation
        );

        $urinePanel = $this->panelId(
            'URINE EXAMINATION', 4, null,
            [$urQty,$urColour,$urApp,$urPH,$urSG,$urUrobi,
             $urProt,$urSugar,$urBlood,$urKetone,$urBileS,$urBileP,
             $urPus,$urRBC,$urEpi,$urCasts,$urCryst,$urBact],
            null
        );

        // ── 5. HEALTH GUARD GOLD PACKAGE ──────────────────────────────────────
        $pkgId = DB::table('test_packages')->where('name', 'HEALTH GUARD GOLD')->value('id');
        if (!$pkgId) {
            $pkgId = DB::table('test_packages')->insertGetId([
                'name'       => 'HEALTH GUARD GOLD',
                'fee'        => null,
                'gender'     => 'Both',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Link all panels to the package (skip if already linked)
        $panels = [3, $esrPanel, $hba1cPanel, $rftPanel, $biochemPanel,
                   $lipidPanel, $lftPanel, $urinePanel, 4]; // 3=CBC, 4=TFT
        foreach ($panels as $panelId) {
            $exists = DB::table('test_package_panel')
                ->where('test_package_id', $pkgId)
                ->where('test_panel_id', $panelId)
                ->exists();
            if (!$exists) {
                DB::table('test_package_panel')->insert([
                    'test_package_id' => $pkgId,
                    'test_panel_id'   => $panelId,
                ]);
            }
        }

        $this->command->info('HEALTH GUARD GOLD setup complete.');
        $this->command->info('Package ID: ' . $pkgId);
        $this->command->info('Panels linked: ' . count($panels));
    }

    // ── HELPERS ───────────────────────────────────────────────────────────────

    private function unitId(string $name): int
    {
        $existing = DB::table('units')->where('name', $name)->value('id');
        if ($existing) return $existing;
        return DB::table('units')->insertGetId([
            'name'       => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function testId(
        string $name,
        ?string $shortName,
        int $categoryId,
        int $unitId,
        string $inputType,
        ?string $defaultResult,
        ?string $interpretation = null
    ): int {
        $existing = DB::table('tests')->where('name', $name)->value('id');
        if ($existing) return $existing;
        return DB::table('tests')->insertGetId([
            'name'                   => $name,
            'short_name'             => $shortName,
            'category_id'            => $categoryId,
            'unit_id'                => $unitId,
            'input_type'             => $inputType,
            'default_result'         => $defaultResult,
            'default_result_female'  => $defaultResult,
            'default_result_other'   => $defaultResult,
            'optional'               => 0,
            'interpretation'         => $interpretation,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }

    private function panelId(
        string $name,
        int $categoryId,
        ?float $price,
        array $testIds,
        ?string $interpretation
    ): int {
        $existing = DB::table('test_panels')->where('name', $name)->value('id');
        if ($existing) return $existing;
        return DB::table('test_panels')->insertGetId([
            'name'                   => $name,
            'category_id'            => $categoryId,
            'price'                  => $price,
            'hide_interpretation'    => 0,
            'hide_method_instrument' => 0,
            'tests'                  => json_encode(array_map('strval', $testIds)),
            'interpretation'         => $interpretation,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }
}
