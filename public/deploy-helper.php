<?php
/**
 * Helper Deployment Otomatis untuk cPanel Tanpa SSH / Terminal
 * Akademi Langgas Sinau
 * 
 * Akses via browser: https://domain-anda.com/deploy-helper.php?key=langgas2026
 * Setelah selesai, klik tombol HAPUS di halaman ini untuk keamanan.
 */

$securityKey = 'langgas2026';

if (!isset($_GET['key']) || $_GET['key'] !== $securityKey) {
    http_response_code(403);
    die('<h3 style="color:red; font-family:sans-serif; text-align:center; margin-top:50px;">Akses Ditolak. Kunci keamanan tidak valid.</h3>');
}

// Aksi hapus diri sendiri
if (isset($_POST['action']) && $_POST['action'] === 'self_delete') {
    unlink(__FILE__);
    die('<div style="font-family:sans-serif; padding:20px; text-align:center; color:green;"><h3>File deploy-helper.php telah berhasil dihapus. Sistem Anda aman!</h3><a href="/">Ke Halaman Utama</a></div>');
}

$results = [];

// 1. Cek Versi PHP & Ekstensi
$phpVersion = phpversion();
$isPhpOk = version_compare($phpVersion, '8.2.0', '>=');
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'curl'];
$missingExtensions = [];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $missingExtensions[] = $ext;
    }
}
$results['php_version'] = [
    'title' => 'Versi PHP',
    'status' => $isPhpOk,
    'message' => "PHP {$phpVersion} " . ($isPhpOk ? '(Memenuhi syarat PHP >= 8.2)' : '(Peringatan: Direkomendasikan PHP 8.2 atau 8.3 di cPanel)'),
];
$results['php_extensions'] = [
    'title' => 'Ekstensi PHP Wajib',
    'status' => empty($missingExtensions),
    'message' => empty($missingExtensions) ? 'Semua ekstensi PHP wajib telah aktif' : 'Ekstensi belum aktif: ' . implode(', ', $missingExtensions),
];

// 2. Cek File .env
$envPath = __DIR__ . '/../.env';
$hasEnv = file_exists($envPath);
$results['env_file'] = [
    'title' => 'Berkas Konfigurasi (.env)',
    'status' => $hasEnv,
    'message' => $hasEnv ? 'Berkas .env ditemukan di root aplikasi' : 'Berkas .env tidak ditemukan! Silakan salin .env.production.example menjadi .env',
];

// 3. Symlink Storage
$storageTarget = __DIR__ . '/../storage/app/public';
$storageLink = __DIR__ . '/storage';
$symlinkOk = false;
$symlinkMsg = '';

if (is_link($storageLink) || is_dir($storageLink)) {
    $symlinkOk = true;
    $symlinkMsg = 'Symlink public/storage sudah terpasang dengan baik.';
} else {
    if (file_exists($storageTarget)) {
        try {
            if (function_exists('symlink')) {
                symlink($storageTarget, $storageLink);
                $symlinkOk = true;
                $symlinkMsg = 'Berhasil membuat symlink public/storage ke storage/app/public.';
            } else {
                $symlinkMsg = 'Fungsi symlink() dinonaktifkan di hosting ini. Silakan buat symlink via File Manager cPanel atau hubungi support hosting.';
            }
        } catch (\Throwable $e) {
            $symlinkMsg = 'Gagal membuat symlink: ' . $e->getMessage();
        }
    } else {
        $symlinkMsg = 'Direktori target storage/app/public belum dibuat.';
    }
}
$results['symlink'] = [
    'title' => 'Storage Symlink (Foto & Berkas Siswa)',
    'status' => $symlinkOk,
    'message' => $symlinkMsg,
];

// 4. Inisialisasi Framework Laravel & Cek DB / Cache
$laravelOk = false;
$laravelMsg = '';
if (file_exists(__DIR__ . '/../vendor/autoload.php') && file_exists(__DIR__ . '/../bootstrap/app.php')) {
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        // Cek Koneksi DB
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbName = \Illuminate\Support\Facades\DB::connection()->getDatabaseName();
        $locCount = \App\Models\AttendanceLocation::count();
        $adminCount = \App\Models\User::where('role', 'admin')->count();

        $laravelOk = true;
        $laravelMsg = "Koneksi database MySQL '{$dbName}' berhasil! Ditemukan {$locCount} titik GPS dan {$adminCount} akun Admin.";

        // Jalankan Optimize jika diminta
        if (isset($_GET['optimize']) && $_GET['optimize'] == '1') {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            \Illuminate\Support\Facades\Artisan::call('config:cache');
            \Illuminate\Support\Facades\Artisan::call('route:cache');
            \Illuminate\Support\Facades\Artisan::call('view:cache');
            $laravelMsg .= " Cache konfigurasi, rute, dan blade view berhasil dibuat!";
        }
    } catch (\Throwable $e) {
        $laravelMsg = 'Koneksi ke Laravel / Database gagal: ' . $e->getMessage();
    }
} else {
    $laravelMsg = 'Folder vendor/ atau bootstrap/ tidak ditemukan. Pastikan seluruh folder project telah diekstrak.';
}

$results['database'] = [
    'title' => 'Koneksi Database MySQL & Booting Laravel',
    'status' => $laravelOk,
    'message' => $laravelMsg,
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deployment Helper - Akademi Langgas Sinau</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #f8fafc; color: #1e293b; padding: 30px 15px; margin: 0; }
        .container { max-width: 680px; margin: 0 auto; background: #ffffff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); padding: 30px; border: 1px solid #e2e8f0; }
        h1 { font-size: 20px; color: #1e1b4b; margin-top: 0; margin-bottom: 6px; }
        p.subtitle { color: #64748b; font-size: 13px; margin-bottom: 24px; }
        .item { padding: 14px 16px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #e2e8f0; display: flex; align-items: flex-start; gap: 12px; }
        .item.ok { background: #f0fdf4; border-color: #bbf7d0; }
        .item.fail { background: #fef2f2; border-color: #fecaca; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge.ok { background: #15803d; color: white; }
        .badge.fail { background: #b91c1c; color: white; }
        .item-title { font-size: 13px; font-weight: bold; margin-bottom: 2px; }
        .item-msg { font-size: 12px; color: #475569; }
        .actions { margin-top: 24px; padding-top: 20px; border-top: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between; align-items: center; }
        .btn { padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; border: none; }
        .btn-primary { background: #4f46e5; color: white; }
        .btn-primary:hover { background: #4338ca; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-secondary { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
        .btn-secondary:hover { background: #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Deployment Helper - Akademi Langgas Sinau</h1>
        <p class="subtitle">Skrip diagnosa & pemasangan instan untuk cPanel / Shared Hosting.</p>

        <?php foreach ($results as $res): ?>
            <div class="item <?= $res['status'] ? 'ok' : 'fail' ?>">
                <span class="badge <?= $res['status'] ? 'ok' : 'fail' ?>"><?= $res['status'] ? 'OK' : 'PERHATIAN' ?></span>
                <div style="flex: 1;">
                    <div class="item-title"><?= htmlspecialchars($res['title']) ?></div>
                    <div class="item-msg"><?= htmlspecialchars($res['message']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="actions">
            <div>
                <a href="deploy-helper.php?key=<?= urlencode($securityKey) ?>&optimize=1" class="btn btn-secondary">⚡ Optimasi Cache Laravel</a>
                <a href="/" class="btn btn-primary">Buka Website &rarr;</a>
            </div>
            <form method="POST" onsubmit="return confirm('Yakin ingin menghapus skrip deploy-helper.php ini? Tindakan ini tidak dapat dibatalkan.');">
                <input type="hidden" name="action" value="self_delete">
                <button type="submit" class="btn btn-danger">🗑️ Hapus Skrip Ini (Wajib Demi Keamanan)</button>
            </form>
        </div>
    </div>
</body>
</html>
