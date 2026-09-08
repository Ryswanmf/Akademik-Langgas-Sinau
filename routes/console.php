<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Otomatis tandai Alpa bagi siswa yang tidak hadir setiap akhir hari kerja pukul 17:01 WIB
Schedule::command('attendance:auto-alpa')
    ->dailyAt('17:01')
    ->timezone('Asia/Jakarta')
    ->description('Evaluasi harian Auto-Alpa bagi siswa yang tidak presensi');
