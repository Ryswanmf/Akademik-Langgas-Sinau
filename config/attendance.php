<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LKP Langgas Sinau Location & Coordinates
    |--------------------------------------------------------------------------
    | Koordinat resmi lokasi LKP Langgas Sinau untuk acuan geolokasi presensi.
    */
    'lkp_name' => env('LKP_NAME', 'LKP Langgas Sinau Akademi'),
    'address' => env('LKP_ADDRESS', 'Gg. Cendana, Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung 34181'),
    
    // Titik Latitude & Longitude LKP Langgas Sinau (Gg. Cendana, Banjar Rejo, Kec. Batanghari, Kab. Lampung Timur)
    'latitude' => (float) env('LKP_LATITUDE', -5.1240),
    'longitude' => (float) env('LKP_LONGITUDE', 105.3370),

    // Radius toleransi kehadiran dalam meter (misal: 150 meter)
    'radius_meters' => (int) env('LKP_RADIUS_METERS', 150),

    // Kunci Radius GPS Ketat (true = wajib dalam radius <= 150m, false = toleransi)
    'strict_radius' => (bool) env('LKP_STRICT_RADIUS', true),

    // Hari Operasional Pelatihan (1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu, 0=Minggu)
    'working_days' => [1, 2, 3, 4, 5, 6],

    // Batas waktu Auto-Alpa di akhir hari kerja (WIB)
    'auto_alpa_time' => env('LKP_AUTO_ALPA_TIME', '17:00'),

    /*
    |--------------------------------------------------------------------------
    | Aturan Waktu Presensi Masuk & Keluar (Pulang)
    |--------------------------------------------------------------------------
    | 1. Jam 08.00 - 09.30 : Hadir Jam Masuk
    | 2. Jam 09.31 - 13.50 : Terlambat Jam Masuk
    | 3. Jam 14.00 - 17.00 : Hadir Jam Keluar (Pulang)
    */
    'in_start' => env('LKP_IN_START', '08:00'),
    'in_on_time_end' => env('LKP_IN_ON_TIME_END', '09:30'),
    'in_late_end' => env('LKP_IN_LATE_END', '13:50'),

    'out_start' => env('LKP_OUT_START', '14:00'),
    'out_end' => env('LKP_OUT_END', '17:00'),
];
