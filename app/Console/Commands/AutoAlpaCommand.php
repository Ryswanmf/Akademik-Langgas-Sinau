<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoAlpaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-alpa {--date= : Tanggal evaluasi presensi (YYYY-MM-DD)} {--force : Paksa jalankan tanpa mengecek hari libur atau batas jam}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tandai otomatis siswa aktif yang tidak melakukan presensi sebagai Alpa di akhir hari';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDateStr = $this->option('date') ?: Carbon::today()->format('Y-m-d');
        $targetDate = Carbon::parse($targetDateStr);
        $force = (bool) $this->option('force');

        $this->info("Memulai evaluasi Auto-Alpa untuk tanggal: {$targetDate->translatedFormat('d F Y')}");

        // Cek hari libur jika bukan dipaksa
        $workingDays = config('attendance.working_days', [1, 2, 3, 4, 5, 6]);
        if (!$force && !in_array($targetDate->dayOfWeek, $workingDays)) {
            $this->warn("Tanggal {$targetDateStr} adalah hari libur pelatihan (Minggu). Auto-Alpa dilewati.");
            return self::SUCCESS;
        }

        // Cek jam operasional jika tanggal adalah hari ini dan tidak diforce
        $autoAlpaTime = config('attendance.auto_alpa_time', '17:00');
        if (!$force && $targetDate->isToday() && Carbon::now()->format('H:i') < $autoAlpaTime) {
            $this->warn("Saat ini belum melewati batas jam operasional ({$autoAlpaTime} WIB). Gunakan opsi --force untuk memaksa eksekusi.");
            return self::FAILURE;
        }

        // Ambil semua siswa aktif
        $activeStudents = Student::with('user')->where('status', 'aktif')->get();
        if ($activeStudents->isEmpty()) {
            $this->info("Tidak ada siswa aktif yang terdaftar.");
            return self::SUCCESS;
        }

        $countCreated = 0;
        $countSkipped = 0;

        foreach ($activeStudents as $student) {
            // Cek apakah sudah ada catatan absensi hari ini
            $attendance = Attendance::where('student_id', $student->id)
                ->whereDate('date', $targetDateStr)
                ->first();

            if ($attendance) {
                $countSkipped++;
                continue;
            }

            // Cek apakah ada perizinan (pending)
            $pendingPermission = Permission::where('student_id', $student->id)
                ->whereDate('date', $targetDateStr)
                ->where('status', 'menunggu')
                ->first();

            $note = $pendingPermission 
                ? "Otomatis Alpa oleh Sistem (Terdapat pengajuan {$pendingPermission->type} yang masih menunggu verifikasi admin)"
                : "Otomatis Alpa oleh Sistem (Tidak melakukan presensi harian hingga batas pukul {$autoAlpaTime} WIB)";

            Attendance::create([
                'student_id' => $student->id,
                'date' => $targetDateStr,
                'status' => 'alpa',
                'note' => $note,
            ]);

            $countCreated++;
        }

        $this->info("Evaluasi selesai! {$countCreated} siswa dicatat Alpa, {$countSkipped} siswa dilewati (sudah memiliki status presensi).");

        return self::SUCCESS;
    }
}
