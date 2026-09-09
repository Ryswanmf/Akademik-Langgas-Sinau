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

Artisan::command('students:clear {--force : Jalankan penghapusan tanpa konfirmasi}', function () {
    if (!$this->option('force') && !$this->confirm('Apakah Anda yakin ingin menghapus seluruh data siswa dummy (presensi, nilai, sertifikat, izin, akun siswa)? Data lokasi GPS dan akun admin AKAN TETAP AMAN.')) {
        $this->warn('Operasi dibatalkan.');
        return;
    }

    $this->info('Memulai pembersihan data dummy siswa...');

    $countCertificates = \App\Models\Certificate::count();
    $countGrades = \App\Models\Grade::count();
    $countPermissions = \App\Models\Permission::count();
    $countAttendances = \App\Models\Attendance::count();
    $countStudents = \App\Models\Student::count();
    $countSiswaUsers = \App\Models\User::where('role', 'siswa')->count();

    \Illuminate\Support\Facades\DB::transaction(function () {
        // Hapus data yang berelasi dengan siswa
        \App\Models\Certificate::query()->delete();
        \App\Models\Grade::query()->delete();
        \App\Models\Permission::query()->delete();
        \App\Models\Attendance::query()->delete();

        // Hapus data siswa
        \App\Models\Student::query()->delete();

        // Hapus akun user dengan role siswa
        \App\Models\User::where('role', 'siswa')->delete();
    });

    $adminCount = \App\Models\User::where('role', 'admin')->count();
    $locationCount = \App\Models\AttendanceLocation::count();

    $this->table(
        ['Kategori Data', 'Status', 'Jumlah'],
        [
            ['Sertifikat Siswa', 'Dihapus', $countCertificates],
            ['Nilai Siswa', 'Dihapus', $countGrades],
            ['Perizinan Siswa', 'Dihapus', $countPermissions],
            ['Presensi Siswa', 'Dihapus', $countAttendances],
            ['Profil Siswa', 'Dihapus', $countStudents],
            ['Akun User Siswa', 'Dihapus', $countSiswaUsers],
            ['Akun Administrator', 'Aman (Dipertahankan)', $adminCount],
            ['Titik Lokasi GPS & Waktu', 'Aman (Dipertahankan)', $locationCount],
        ]
    );

    $this->info('✓ Seluruh data dummy siswa berhasil dibersihkan.');
    $this->info('✓ Data lokasi GPS dan akun admin tetap utuh.');
    $this->info('✓ Sekarang Anda dapat menginput ulang data siswa baru.');
})->purpose('Hapus seluruh data dummy siswa dan relasinya tanpa menghapus data lokasi GPS dan admin');
