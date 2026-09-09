<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin as AdminController;
use App\Http\Controllers\Siswa as SiswaController;

// Public & Authentication Routes
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('siswa.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController\DashboardController::class, 'index'])->name('dashboard');

    // Data Siswa
    Route::patch('/students/{student}/status', [AdminController\StudentController::class, 'updateStatus'])->name('students.update-status');
    Route::resource('students', AdminController\StudentController::class);

    // Kelas
    Route::resource('classes', AdminController\ClassController::class);

    // Jadwal
    Route::resource('schedules', AdminController\ScheduleController::class)->except(['show']);

    // Absensi (Fitur Utama: Catat, Rekap, & Cetak Hasil per Siswa)
    Route::post('/attendances/auto-alpa', [AdminController\AttendanceController::class, 'runAutoAlpa'])->name('attendances.auto-alpa');
    Route::get('/attendances/rekap', [AdminController\AttendanceController::class, 'rekap'])->name('attendances.rekap');
    Route::get('/attendances/print/{student}', [AdminController\AttendanceController::class, 'printStudentAttendance'])->name('attendances.print-student');
    Route::resource('attendances', AdminController\AttendanceController::class)->except(['show']);

    // Titik GPS & Pengaturan Waktu Absensi
    Route::patch('/attendance-locations/{attendanceLocation}/toggle-active', [AdminController\AttendanceLocationController::class, 'toggleActive'])->name('attendance-locations.toggle-active');
    Route::resource('attendance-locations', AdminController\AttendanceLocationController::class)->except(['show']);

    // Perizinan
    Route::get('/permissions', [AdminController\PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{permission}', [AdminController\PermissionController::class, 'show'])->name('permissions.show');
    Route::patch('/permissions/{permission}/status', [AdminController\PermissionController::class, 'updateStatus'])->name('permissions.update-status');

    // Nilai
    Route::resource('grades', AdminController\GradeController::class)->except(['show']);

    // Nilai & Sertifikat
    Route::patch('/certificates/{certificate}/toggle-publish', [AdminController\CertificateController::class, 'togglePublish'])->name('certificates.toggle-publish');
    Route::get('/certificates/{certificate}/certificate', [AdminController\CertificateController::class, 'showCertificate'])->name('certificates.certificate');
    Route::get('/certificates/{certificate}/print', [AdminController\CertificateController::class, 'print'])->name('certificates.print');
    Route::get('/certificates/{certificate}/download', [AdminController\CertificateController::class, 'download'])->name('certificates.download');
    Route::resource('certificates', AdminController\CertificateController::class)->except(['show']);

    // Pengumuman
    Route::resource('announcements', AdminController\AnnouncementController::class)->except(['show']);
});

// Protected Siswa Routes
Route::middleware(['auth', 'siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaController\DashboardController::class, 'index'])->name('dashboard');

    // Profil Saya
    Route::get('/profile', [SiswaController\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [SiswaController\ProfileController::class, 'update'])->name('profile.update');

    // Jadwal Saya
    Route::get('/schedules', [SiswaController\ScheduleController::class, 'index'])->name('schedules');

    // Absensi Saya (Presensi Masuk & Pulang Swafoto + GPS)
    Route::get('/attendances', [SiswaController\AttendanceController::class, 'index'])->name('attendances');
    Route::post('/attendances/clock-in', [SiswaController\AttendanceController::class, 'clockIn'])->name('attendances.clock-in');
    Route::post('/attendances/clock-out', [SiswaController\AttendanceController::class, 'clockOut'])->name('attendances.clock-out');

    // Pengajuan Izin
    Route::get('/permissions', [SiswaController\PermissionController::class, 'index'])->name('permissions');
    Route::post('/permissions', [SiswaController\PermissionController::class, 'store'])->name('permissions.store');

    // Nilai Saya
    Route::get('/grades', [SiswaController\GradeController::class, 'index'])->name('grades');

    // Nilai & Sertifikat Saya
    Route::get('/certificates', [SiswaController\CertificateController::class, 'index'])->name('certificates');
    Route::get('/certificates/{certificate}/certificate', [SiswaController\CertificateController::class, 'showCertificate'])->name('certificates.certificate');
    Route::get('/certificates/{certificate}/print', [SiswaController\CertificateController::class, 'print'])->name('certificates.print');
    Route::get('/certificates/{certificate}/download', [SiswaController\CertificateController::class, 'download'])->name('certificates.download');

    // Pengumuman
    Route::get('/announcements', [SiswaController\AnnouncementController::class, 'index'])->name('announcements');
    Route::get('/announcements/{announcement}', [SiswaController\AnnouncementController::class, 'show'])->name('announcements.show');
});

// ==============================================================================
// FALLBACK MEDIA STREAMING (PENTING UNTUK cPANEL / SHARED HOSTING)
// Memastikan gambar/berkas storage & uploads tetap tampil meskipun symlink cPanel belum dibuat / dinonaktifkan hosting
// ==============================================================================
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }

    $mimeType = @mime_content_type($filePath) ?: 'application/octet-stream';
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.fallback');

Route::get('/uploads/{path}', function (string $path) {
    $candidates = [
        public_path('uploads/' . $path),
        base_path('public/uploads/' . $path),
        base_path('../public_html/uploads/' . $path),
        storage_path('app/public/uploads/' . $path),
    ];

    foreach ($candidates as $filePath) {
        if (file_exists($filePath) && !is_dir($filePath)) {
            $mimeType = @mime_content_type($filePath) ?: 'application/octet-stream';
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    }

    abort(404);
})->where('path', '.*')->name('uploads.fallback');

