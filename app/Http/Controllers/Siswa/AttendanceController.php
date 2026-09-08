<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;
        $today = Carbon::today()->format('Y-m-d');

        // Hari ini attendance
        $todayAttendance = Attendance::with('schedule')
            ->where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        // Hari ini jadwal
        $todaySchedule = Schedule::where('class_id', $student->class_id)
            ->whereDate('date', $today)
            ->first();

        $query = Attendance::with('schedule')
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(10)->withQueryString();
        $stats = $student->attendanceStats();
        $lkpConfig = config('attendance');
        $workingDays = config('attendance.working_days', [1, 2, 3, 4, 5, 6]);
        $isHoliday = !in_array(Carbon::today()->dayOfWeek, $workingDays);

        return view('siswa.attendances', compact(
            'student',
            'attendances',
            'stats',
            'todayAttendance',
            'todaySchedule',
            'lkpConfig',
            'isHoliday'
        ));
    }

    /**
     * Presensi Masuk (Swafoto + GPS)
     */
    public function clockIn(Request $request)
    {
        $student = auth()->user()->student;
        $today = Carbon::today()->format('Y-m-d');

        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'schedule_id' => ['nullable', 'exists:schedules,id'],
            'photo_base64' => ['nullable', 'string'],
            'photo_file' => ['nullable', 'image', 'max:5120'],
        ]);

        if (!$request->filled('photo_base64') && !$request->hasFile('photo_file')) {
            return back()->with('error', 'Swafoto (selfie) wajib diambil sebelum melakukan presensi masuk.');
        }

        $attendance = Attendance::firstOrNew([
            'student_id' => $student->id,
            'date' => $today,
        ]);

        if ($attendance->exists && $attendance->check_in_time) {
            return back()->with('error', 'Anda sudah melakukan presensi masuk hari ini pada pukul ' . $attendance->formatted_check_in_time);
        }

        // Cek hari libur kegiatan pelatihan (Minggu)
        $workingDays = config('attendance.working_days', [1, 2, 3, 4, 5, 6]);
        if (!in_array(Carbon::today()->dayOfWeek, $workingDays) && !app()->runningUnitTests()) {
            return back()->with('error', 'Hari ini adalah hari libur kegiatan pelatihan (Minggu). Presensi harian tidak aktif.');
        }

        // Hitung jarak ke lokasi LKP Langgas Sinau (Banjar Rejo, Batanghari)
        $lkpLat = (float) config('attendance.latitude', -5.1240);
        $lkpLng = (float) config('attendance.longitude', 105.3370);
        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = Attendance::calculateDistance($userLat, $userLng, $lkpLat, $lkpLng);

        // Kunci radius GPS ketat
        $strictRadius = config('attendance.strict_radius', true);
        $maxRadius = (int) config('attendance.radius_meters', 150);
        if ($strictRadius && $distance > $maxRadius) {
            return back()->with('error', "Presensi ditolak! Posisi Anda terdeteksi berjarak {$distance} meter dari LKP Langgas Sinau (Batas maksimal radius presensi adalah {$maxRadius} meter). Pastikan Anda berada di lingkungan pelatihan LKP.");
        }

        // Evaluasi Aturan Waktu Masuk:
        // 1. Jam 08.00 s/d 09.30 : Hadir Jam Masuk
        // 2. Jam 09.31 s/d 13.50 : Terlambat
        // 3. Lewat 13.50         : Jam Masuk Ditutup
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        if ($currentTime <= '09:30') {
            $status = 'hadir';
            $statusLabel = 'Hadir Tepat Waktu';
            $defaultNote = "Presensi Masuk (Hadir Tepat Waktu - Jarak {$distance}m dari LKP)";
        } elseif ($currentTime <= '13:50') {
            $status = 'terlambat';
            $statusLabel = 'Terlambat';
            $defaultNote = "Presensi Masuk (Terlambat pukul {$now->format('H:i')} WIB - Jarak {$distance}m dari LKP)";
        } else {
            return back()->with('error', 'Batas waktu presensi masuk hari ini telah berakhir pada pukul 13:50 WIB.');
        }

        // Upload Swafoto
        $photoPath = $this->handlePhotoUpload($request, 'masuk', $student->id);

        $attendance->schedule_id = $request->schedule_id ?: $attendance->schedule_id;
        $attendance->check_in_time = $now->format('H:i:s');
        $attendance->check_in_lat = $userLat;
        $attendance->check_in_lng = $userLng;
        $attendance->check_in_distance = $distance;
        if ($photoPath) {
            $attendance->check_in_photo = $photoPath;
        }
        $attendance->status = $status;
        $attendance->note = $attendance->note ?: $defaultNote;
        $attendance->save();

        $maxRadius = config('attendance.radius_meters', 150);
        $radiusMsg = $distance <= $maxRadius 
            ? "Berada di dalam radius LKP ({$distance} meter)." 
            : "Catatan: Anda berjarak {$distance} meter dari LKP.";

        $successText = $status === 'hadir' 
            ? "Presensi Masuk berhasil dicatat pukul {$attendance->formatted_check_in_time} (Hadir Tepat Waktu)! {$radiusMsg}"
            : "Presensi Masuk dicatat pukul {$attendance->formatted_check_in_time} (Status: Terlambat). {$radiusMsg}";

        return back()->with('success', $successText);
    }

    /**
     * Presensi Pulang (Swafoto + GPS)
     */
    public function clockOut(Request $request)
    {
        $student = auth()->user()->student;
        $today = Carbon::today()->format('Y-m-d');

        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'photo_base64' => ['nullable', 'string'],
            'photo_file' => ['nullable', 'image', 'max:5120'],
        ]);

        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return back()->with('error', 'Anda harus melakukan presensi masuk terlebih dahulu sebelum presensi pulang.');
        }

        if ($attendance->check_out_time) {
            return back()->with('error', 'Anda sudah melakukan presensi pulang hari ini pada pukul ' . $attendance->formatted_check_out_time);
        }

        if (!$request->filled('photo_base64') && !$request->hasFile('photo_file')) {
            return back()->with('error', 'Swafoto (selfie) kepulangan wajib diambil.');
        }

        // Cek hari libur kegiatan pelatihan (Minggu)
        $workingDays = config('attendance.working_days', [1, 2, 3, 4, 5, 6]);
        if (!in_array(Carbon::today()->dayOfWeek, $workingDays) && !app()->runningUnitTests()) {
            return back()->with('error', 'Hari ini adalah hari libur kegiatan pelatihan (Minggu). Presensi harian tidak aktif.');
        }

        // Evaluasi Aturan Jam Keluar:
        // Jam 14.00 sampai 17.00 : Hadir Jam Keluar
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        if ($currentTime < '14:00') {
            return back()->with('error', 'Presensi jam keluar (pulang) baru dapat dilakukan mulai pukul 14:00 WIB hingga 17:00 WIB.');
        }

        // Hitung jarak ke lokasi LKP Langgas Sinau (Banjar Rejo, Batanghari)
        $lkpLat = (float) config('attendance.latitude', -5.1240);
        $lkpLng = (float) config('attendance.longitude', 105.3370);
        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $distance = Attendance::calculateDistance($userLat, $userLng, $lkpLat, $lkpLng);

        // Kunci radius GPS ketat
        $strictRadius = config('attendance.strict_radius', true);
        $maxRadius = (int) config('attendance.radius_meters', 150);
        if ($strictRadius && $distance > $maxRadius) {
            return back()->with('error', "Presensi pulang ditolak! Posisi Anda terdeteksi berjarak {$distance} meter dari LKP Langgas Sinau (Batas maksimal radius presensi adalah {$maxRadius} meter).");
        }

        // Upload Swafoto Pulang
        $photoPath = $this->handlePhotoUpload($request, 'pulang', $student->id);

        $attendance->check_out_time = $now->format('H:i:s');
        $attendance->check_out_lat = $userLat;
        $attendance->check_out_lng = $userLng;
        $attendance->check_out_distance = $distance;
        if ($photoPath) {
            $attendance->check_out_photo = $photoPath;
        }

        $outLabel = ($currentTime <= '17:00') ? 'Hadir Jam Keluar' : 'Jam Keluar (Lembur)';
        $attendance->note .= " | {$outLabel} pukul {$now->format('H:i')} WIB";
        $attendance->save();

        $maxRadius = config('attendance.radius_meters', 150);
        $radiusMsg = $distance <= $maxRadius 
            ? "Berada di dalam radius LKP ({$distance} meter)." 
            : "Catatan: Jarak kepulangan Anda {$distance} meter dari LKP.";

        return back()->with('success', "Presensi Pulang ({$outLabel}) berhasil dicatat pukul {$attendance->formatted_check_out_time}! {$radiusMsg}");
    }

    /**
     * Handler untuk menyimpan swafoto (Base64 dari Kamera Web atau Upload Berkas)
     */
    protected function handlePhotoUpload(Request $request, string $prefix, int $studentId): ?string
    {
        // 1. Dari Base64 Canvas Kamera
        if ($request->filled('photo_base64')) {
            $data = $request->photo_base64;
            if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
                $data = substr($data, strpos($data, ',') + 1);
                $type = strtolower($type[1]);
                if (!in_array($type, ['jpg', 'jpeg', 'png', 'webp'])) {
                    $type = 'jpg';
                }
                $data = base64_decode($data);
                if ($data !== false) {
                    $filename = $prefix . '_' . $studentId . '_' . time() . '.' . $type;
                    $uploadDir = public_path('uploads/attendances');
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    file_put_contents($uploadDir . '/' . $filename, $data);
                    return 'uploads/attendances/' . $filename;
                }
            }
        }

        // 2. Dari File Upload biasa / Kamera Native HP
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $filename = $prefix . '_' . $studentId . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/attendances');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $filename);
            return 'uploads/attendances/' . $filename;
        }

        return null;
    }
}

