<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
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
        $todayAttendance = Attendance::with(['schedule', 'attendanceLocation'])
            ->where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        // Hari ini jadwal
        $todaySchedule = Schedule::where('class_id', $student->class_id)
            ->whereDate('date', $today)
            ->first();

        $query = Attendance::with(['schedule', 'attendanceLocation'])
            ->where('student_id', $student->id)
            ->orderBy('date', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->paginate(10)->withQueryString();
        $stats = $student->attendanceStats();

        // Ambil data lokasi GPS & aturan waktu aktif dari database
        $primaryLocation = AttendanceLocation::getPrimary();
        $lkpConfig = [
            'lkp_name' => $primaryLocation->name,
            'address' => $primaryLocation->address,
            'plus_code' => $primaryLocation->plus_code,
            'latitude' => (float) $primaryLocation->latitude,
            'longitude' => (float) $primaryLocation->longitude,
            'radius_meters' => (int) $primaryLocation->radius_meters,
            'strict_radius' => (bool) $primaryLocation->strict_radius,
            'working_days' => $primaryLocation->working_days ?? [1, 2, 3, 4, 5, 6],
            'in_start' => $primaryLocation->in_start,
            'in_on_time_end' => $primaryLocation->in_on_time_end,
            'in_late_end' => $primaryLocation->in_late_end,
            'out_start' => $primaryLocation->out_start,
            'out_end' => $primaryLocation->out_end,
            'auto_alpa_time' => $primaryLocation->auto_alpa_time,
        ];
        $workingDays = $lkpConfig['working_days'];
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

        // Hitung jarak ke lokasi LKP aktif terdekat
        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $nearest = AttendanceLocation::findNearestActive($userLat, $userLng);
        $activeLocation = $nearest ? $nearest['location'] : AttendanceLocation::getPrimary();
        $distance = $nearest ? $nearest['distance'] : Attendance::calculateDistance($userLat, $userLng, (float) $activeLocation->latitude, (float) $activeLocation->longitude);

        // Cek hari libur kegiatan pelatihan
        $workingDays = $activeLocation->working_days ?? [1, 2, 3, 4, 5, 6];
        if (!in_array(Carbon::today()->dayOfWeek, $workingDays) && !app()->runningUnitTests()) {
            return back()->with('error', 'Hari ini adalah hari libur kegiatan pelatihan. Presensi harian tidak aktif.');
        }

        // Kunci radius GPS ketat
        $strictRadius = $activeLocation->strict_radius;
        $maxRadius = (int) $activeLocation->radius_meters;
        if ($strictRadius && $distance > $maxRadius) {
            return back()->with('error', "Presensi ditolak! Posisi Anda terdeteksi berjarak {$distance} meter dari {$activeLocation->name} (Batas maksimal radius presensi adalah {$maxRadius} meter). Pastikan Anda berada di lingkungan pelatihan LKP.");
        }

        // Evaluasi Aturan Waktu Masuk:
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        if ($currentTime <= $activeLocation->in_on_time_end) {
            $status = 'hadir';
            $statusLabel = 'Hadir Tepat Waktu';
            $defaultNote = "Presensi Masuk (Hadir Tepat Waktu - Jarak {$distance}m dari {$activeLocation->name})";
        } elseif ($currentTime <= $activeLocation->in_late_end) {
            $status = 'terlambat';
            $statusLabel = 'Terlambat';
            $defaultNote = "Presensi Masuk (Terlambat pukul {$now->format('H:i')} WIB - Jarak {$distance}m dari {$activeLocation->name})";
        } else {
            return back()->with('error', "Batas waktu presensi masuk hari ini telah berakhir pada pukul {$activeLocation->in_late_end} WIB.");
        }

        // Upload Swafoto
        $photoPath = $this->handlePhotoUpload($request, 'masuk', $student->id);

        $attendance->schedule_id = $request->schedule_id ?: $attendance->schedule_id;
        $attendance->attendance_location_id = $activeLocation->id;
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

        $radiusMsg = $distance <= $maxRadius 
            ? "Berada di dalam radius lokasi ({$distance} meter)." 
            : "Catatan: Anda berjarak {$distance} meter dari lokasi.";

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

        // Ambil lokasi terkait dari check-in atau cari lokasi aktif terdekat
        $userLat = (float) $request->latitude;
        $userLng = (float) $request->longitude;

        $activeLocation = $attendance->attendanceLocation;
        if (!$activeLocation) {
            $nearest = AttendanceLocation::findNearestActive($userLat, $userLng);
            $activeLocation = $nearest ? $nearest['location'] : AttendanceLocation::getPrimary();
        }

        $distance = Attendance::calculateDistance($userLat, $userLng, (float) $activeLocation->latitude, (float) $activeLocation->longitude);

        // Cek hari libur kegiatan pelatihan
        $workingDays = $activeLocation->working_days ?? [1, 2, 3, 4, 5, 6];
        if (!in_array(Carbon::today()->dayOfWeek, $workingDays) && !app()->runningUnitTests()) {
            return back()->with('error', 'Hari ini adalah hari libur kegiatan pelatihan. Presensi harian tidak aktif.');
        }

        // Evaluasi Aturan Jam Keluar:
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        if ($currentTime < $activeLocation->out_start) {
            return back()->with('error', "Presensi jam keluar (pulang) baru dapat dilakukan mulai pukul {$activeLocation->out_start} WIB hingga {$activeLocation->out_end} WIB.");
        }

        // Kunci radius GPS ketat
        $strictRadius = $activeLocation->strict_radius;
        $maxRadius = (int) $activeLocation->radius_meters;
        if ($strictRadius && $distance > $maxRadius) {
            return back()->with('error', "Presensi pulang ditolak! Posisi Anda terdeteksi berjarak {$distance} meter dari {$activeLocation->name} (Batas maksimal radius presensi adalah {$maxRadius} meter).");
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

        $outLabel = ($currentTime <= $activeLocation->out_end) ? 'Hadir Jam Keluar' : 'Jam Keluar (Lembur)';
        $attendance->note .= " | {$outLabel} pukul {$now->format('H:i')} WIB";
        $attendance->save();

        $radiusMsg = $distance <= $maxRadius 
            ? "Berada di dalam radius lokasi ({$distance} meter)." 
            : "Catatan: Jarak kepulangan Anda {$distance} meter dari lokasi.";

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

