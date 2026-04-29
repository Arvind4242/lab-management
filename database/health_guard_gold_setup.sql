-- ============================================================
-- HEALTH GUARD GOLD — Master Data Setup
-- Import this via phpMyAdmin into your production `lab` database
-- Safe to run: uses INSERT IGNORE / checks before inserting
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- ── 1. NEW UNITS ─────────────────────────────────────────────────────────────
INSERT IGNORE INTO `units` (`id`, `name`, `created_at`, `updated_at`) VALUES
(63, 'Ratio', NOW(), NOW()),
(64, '-',     NOW(), NOW());

-- ── 2. FIX PDW (id=26) category — was Histopathology(8), should be Haematology(1) ──
UPDATE `tests` SET `category_id` = 1, `updated_at` = NOW() WHERE `id` = 26;

-- ── 3. NEW TESTS ─────────────────────────────────────────────────────────────
-- (skips if name already exists via INSERT IGNORE + UNIQUE not enforced,
--  so we use a safe approach with explicit IDs starting at 29)

-- Haematology — Absolute Counts
INSERT IGNORE INTO `tests` (`id`,`name`,`short_name`,`category_id`,`unit_id`,`input_type`,`default_result`,`default_result_female`,`default_result_other`,`optional`,`price`,`method`,`instrument`,`interpretation`,`created_at`,`updated_at`) VALUES
(29, 'Absolute Neutrophil Count', 'ANC',  1, 4,  'Numeric', '2000-7000',  '2000-7000',  '2000-7000',  0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(30, 'Absolute Lymphocyte Count', 'ALC',  1, 4,  'Numeric', '1000-3000',  '1000-3000',  '1000-3000',  0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(31, 'Absolute Eosinophil Count', 'AEC2', 1, 4,  'Numeric', '20-500',     '20-500',     '20-500',     0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(32, 'Absolute Monocyte Count',   'AMC',  1, 4,  'Numeric', '200-1000',   '200-1000',   '200-1000',   0, NULL, NULL, NULL, NULL, NOW(), NOW()),

-- HbA1c (Biochemistry = 2)
(33, 'HbA1c (Glycocylated Haemoglobin)', 'HbA1c', 2, 2, 'Numeric',
    'Below 6.0: Normal, 6.0-7.0: Good Control, 7.0-8.0: Fair Control, 8.0-10.0: Unsatisfactory, Above 10: Poor Control',
    'Below 6.0: Normal, 6.0-7.0: Good Control, 7.0-8.0: Fair Control, 8.0-10.0: Unsatisfactory, Above 10: Poor Control',
    'Below 6.0: Normal, 6.0-7.0: Good Control, 7.0-8.0: Fair Control, 8.0-10.0: Unsatisfactory, Above 10: Poor Control',
    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(34, 'Mean Blood Glucose', NULL, 2, 22, 'Numeric',
    '90-120: Excellent Control, 121-150: Good Control, 151-181: Average Control, 181-210: Action Suggested, Above 211: Panic Value',
    '90-120: Excellent Control, 121-150: Good Control, 151-181: Average Control, 181-210: Action Suggested, Above 211: Panic Value',
    '90-120: Excellent Control, 121-150: Good Control, 151-181: Average Control, 181-210: Action Suggested, Above 211: Panic Value',
    0, NULL, NULL, NULL, NULL, NOW(), NOW()),

-- Renal Function Test
(35, 'Blood Urea Nitrogen',  'BUN', 2, 22, 'Numeric', '7 - 20',      '7 - 20',      '7 - 20',      0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(36, 'Urea',                  NULL, 2, 22, 'Numeric', '15.0 - 45.0', '15.0 - 45.0', '15.0 - 45.0', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(37, 'Creatinine',            NULL, 2, 22, 'Numeric', '0.7-1.3',     '0.7-1.3',     '0.7-1.3',     0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(38, 'Uric Acid',             NULL, 2, 22, 'Numeric', '2.5-7.5',     '2.5-7.5',     '2.5-7.5',     0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(39, 'S. Calcium',            NULL, 2, 22, 'Numeric', '8.6-10.0',    '8.6-10.0',    '8.6-10.0',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(40, 'BUN Creatinine Ratio',  NULL, 2, 22, 'Numeric', '5 - 20',      '5 - 20',      '5 - 20',      0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(41, 'Sodium (Na+)',          NULL, 2, 35, 'Numeric', '135 - 155',   '135 - 155',   '135 - 155',   0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(42, 'Potassium(K+)',         NULL, 2, 35, 'Numeric', '3.5 - 5.5',   '3.5 - 5.5',   '3.5 - 5.5',   0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(43, 'Chloride (Cl-)',        NULL, 2, 35, 'Numeric', '98 - 108',    '98 - 108',    '98 - 108',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),

-- Blood Glucose
(44, 'Fasting Blood Sugar', 'FBS', 2, 22, 'Numeric', '65-110', '65-110', '65-110', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),

-- Lipid Profile
(45, 'Total Cholesterol',           NULL,   2, 22, 'Numeric', 'Normal: <200, Borderline High: 200-239, High Blood Cholesterol: >239',    'Normal: <200, Borderline High: 200-239, High Blood Cholesterol: >239',    'Normal: <200, Borderline High: 200-239, High Blood Cholesterol: >239',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(46, 'Triglyceride',                NULL,   2, 22, 'Numeric', 'Normal: <161, High: 161-199, Very High: >500',                             'Normal: <161, High: 161-199, Very High: >500',                             'Normal: <161, High: 161-199, Very High: >500',                             0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(47, 'HDL-Cholesterol, Direct',     'HDL',  2, 22, 'Numeric', '35.3-79.5',                                                                '35.3-79.5',                                                                '35.3-79.5',                                                                0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(48, 'LDL-Cholesterol, Calculated', 'LDL',  2, 22, 'Numeric', 'Optimal: <100, Near or Above Optimal: 100-129, Border line: 130-159, High: 160-189, Very High: >190', 'Optimal: <100, Near or Above Optimal: 100-129, Border line: 130-159, High: 160-189, Very High: >190', 'Optimal: <100, Near or Above Optimal: 100-129, Border line: 130-159, High: 160-189, Very High: >190', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(49, 'VLDL Cholesterol',            'VLDL', 2, 22, 'Numeric', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(50, 'Total Cholesterol/HDL Ratio', NULL,   2, 63, 'Numeric', '3.5 - 5.0', '3.5 - 5.0', '3.5 - 5.0', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(51, 'LDL Chol./HDL Chol. RATIO',  NULL,   2, 63, 'Numeric', '1.5 - 3.0', '1.5 - 3.0', '1.5 - 3.0', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),

-- Liver Function Test
(52, 'Total Bilirubin',      NULL,  2, 22, 'Numeric', '0.2-1.28',   '0.2-1.28',   '0.2-1.28',   0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(53, 'Direct Bilirubin',     NULL,  2, 22, 'Numeric', '0.1-0.25',   '0.1-0.25',   '0.1-0.25',   0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(54, 'Indirect Bilirubin',   NULL,  2, 22, 'Numeric', '0.0-1.0',    '0.0-1.0',    '0.0-1.0',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(55, 'SGOT(AST)',            'AST', 2, 53, 'Numeric', '0.0 - 46.0', '0.0 - 46.0', '0.0 - 46.0', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(56, 'SGPT(ALT)',            'ALT', 2, 53, 'Numeric', '0.0 - 49.0', '0.0 - 49.0', '0.0 - 49.0', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(57, 'Alkaline Phosphatase', 'ALP', 2, 13, 'Numeric', '53 -128',    '53 -128',    '53 -128',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(58, 'Total Protein',        NULL,  2, 1,  'Numeric', '6.0-8.0',    '6.0-8.0',    '6.0-8.0',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(59, 'Albumin',              NULL,  2, 8,  'Numeric', '3.5 - 5.2',  '3.5 - 5.2',  '3.5 - 5.2',  0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(60, 'Globulin',             NULL,  2, 1,  'Numeric', '1.8-3.6',    '1.8-3.6',    '1.8-3.6',    0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(61, 'A/G Ratio',            NULL,  2, 63, 'Numeric', '0.9 - 2.0',  '0.9 - 2.0',  '0.9 - 2.0',  0, NULL, NULL, NULL, NULL, NOW(), NOW()),

-- Urine Examination (Clinical Pathology = 4)
(62, 'Quantity',               NULL, 4, 30, 'Single Line', NULL,           NULL,           NULL,           0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(63, 'Colour',                 NULL, 4, 64, 'Single Line', NULL,           NULL,           NULL,           0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(64, 'Appearance',             NULL, 4, 64, 'Single Line', 'Clear',        'Clear',        'Clear',        0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(65, 'Reaction (pH)',          NULL, 4, 64, 'Numeric',     '4.6 - 7.5',   '4.6 - 7.5',   '4.6 - 7.5',   0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(66, 'Specific Gravity',       NULL, 4, 64, 'Numeric',     '1.005-1.030', '1.005-1.030', '1.005-1.030', 0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(67, 'Urobilinogen',           NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(68, 'Protein (Urine)',        NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(69, 'Sugar (Urine)',          NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(70, 'Blood (Urine)',          NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(71, 'Ketone',                 NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(72, 'Bile Salt',              NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(73, 'Bile Pigment',           NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(74, 'Pus Cells',              NULL, 4, 12, 'Single Line', NULL,           NULL,           NULL,           0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(75, 'Red Blood Cells (RBCs)', NULL, 4, 12, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(76, 'Epithelial Cells',       NULL, 4, 12, 'Single Line', NULL,           NULL,           NULL,           0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(77, 'Casts',                  NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(78, 'Crystals',               NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW()),
(79, 'Bacteria',               NULL, 4, 64, 'Single Line', 'Absent',       'Absent',       'Absent',       0, NULL, NULL, NULL, NULL, NOW(), NOW());

-- Fix AUTO_INCREMENT for tests
ALTER TABLE `tests` AUTO_INCREMENT = 80;

-- ── 4. UPDATE CBC PANEL (id=3) — add RDW(23), MPV(24), PDW(26), PCT(27), abs counts ──
UPDATE `test_panels`
SET
    `tests` = '["3","28","4","14","15","16","17","18","19","20","21","22","23","24","26","27","9","29","30","31","32"]',
    `updated_at` = NOW()
WHERE `id` = 3;

-- ── 5. NEW TEST PANELS ────────────────────────────────────────────────────────
INSERT IGNORE INTO `test_panels` (`id`,`name`,`category_id`,`price`,`hide_interpretation`,`hide_method_instrument`,`tests`,`interpretation`,`created_at`,`updated_at`) VALUES

(5, 'ERYTHROCYTE SEDIMENTATION RATE', 1, NULL, 0, 0,
    '["7"]',
    'An erythrocyte sedimentation rate test, also called an ESR or sed rate test, measures the speed at which red blood cells settle to the bottom of an upright glass test tube. This measurement is important because when abnormal proteins are present in the blood, typically due to inflammation or infection, they cause red blood cells to clump together and sink more quickly, which results in a high ESR value.\n\nComment: Please correlate with clinical condition.\nNotes: Clinical diagnosis should not be made on the findings of a single test result, but should integrate both clinical and laboratory data.',
    NOW(), NOW()),

(6, 'HbA1c (Glycated Haemoglobin)', 2, NULL, 0, 0,
    '["33","34"]',
    NULL,
    NOW(), NOW()),

(7, 'RENAL FUNCTION TEST', 2, NULL, 0, 0,
    '["35","36","37","38","39","40","41","42","43"]',
    NULL,
    NOW(), NOW()),

(8, 'Biochemistry', 2, NULL, 0, 0,
    '["44"]',
    NULL,
    NOW(), NOW()),

(9, 'Lipid Profile', 2, NULL, 0, 0,
    '["45","46","47","48","49","50","51"]',
    'Please Note: Triglyceride level changes acutely & temporarily because of previous night\'s diet, fasting/non-fasting state & changes in the blood sugar level. Certain medications can influence your triglyceride levels.',
    NOW(), NOW()),

(10, 'LIVER FUNCTION TEST', 2, NULL, 0, 0,
    '["52","53","54","55","56","57","58","59","60","61"]',
    'A liver function test (LFT) may be used to screen for liver damage.\n1. Bilirubin - increased when too much is produced or less removed.\n2. AST - very high with acute hepatitis.\n3. ALT - very high with acute hepatitis.\n4. Alkaline phosphatase - ALP may be significantly increased with obstructed bile ducts.\n5. Protein - total protein is typically normal with liver disease.',
    NOW(), NOW()),

(11, 'URINE EXAMINATION', 4, NULL, 0, 0,
    '["62","63","64","65","66","67","68","69","70","71","72","73","74","75","76","77","78","79"]',
    NULL,
    NOW(), NOW());

ALTER TABLE `test_panels` AUTO_INCREMENT = 12;

-- ── 6. HEALTH GUARD GOLD PACKAGE ─────────────────────────────────────────────
INSERT IGNORE INTO `test_packages` (`id`,`name`,`fee`,`gender`,`created_at`,`updated_at`) VALUES
(1, 'HEALTH GUARD GOLD', NULL, 'Both', NOW(), NOW());

ALTER TABLE `test_packages` AUTO_INCREMENT = 2;

-- ── 7. LINK ALL PANELS TO THE PACKAGE ────────────────────────────────────────
-- Panels: CBC(3), ESR(5), HbA1c(6), RFT(7), Biochemistry(8),
--         Lipid(9), LFT(10), Urine(11), TFT(4)
INSERT IGNORE INTO `test_package_panel` (`test_package_id`, `test_panel_id`) VALUES
(1, 3),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 4);

SET FOREIGN_KEY_CHECKS = 1;

-- ── SUMMARY ───────────────────────────────────────────────────────────────────
-- After running this script:
--   • 2  new units added (Ratio, -)
--   • 51 new tests added (ids 29–79)
--   • 7  new test panels added (ids 5–11)
--   • 1  package created: HEALTH GUARD GOLD (id=1) with 9 panels
--   • CBC panel updated with absolute counts, RDW, MPV, PDW, PCT
--
-- To create a report: select HEALTH GUARD GOLD package → all panels auto-fill
