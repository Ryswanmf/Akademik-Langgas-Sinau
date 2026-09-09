<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Permission;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with(['student.user', 'student.class', 'schedule'])->latest('date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('school_origin', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('program')) {
            $program = $request->program;
            $query->whereHas('student', function ($q) use ($program) {
                $q->where('program', $program);
            });
        }

        if ($request->filled('class_id')) {
            $classId = $request->class_id;
            $query->whereHas('student', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Clone query for stats
        $statsQuery = clone $query;
        $total = $statsQuery->count();
        $hadir = (clone $query)->where('status', 'hadir')->count();
        $terlambat = (clone $query)->where('status', 'terlambat')->count();
        $izin = (clone $query)->where('status', 'izin')->count();
        $sakit = (clone $query)->where('status', 'sakit')->count();
        $alpa = (clone $query)->where('status', 'alpa')->count();
        $rate = $total > 0 ? round((($hadir + $terlambat) / $total) * 100, 1) : 0;

        $stats = compact('total', 'hadir', 'terlambat', 'izin', 'sakit', 'alpa', 'rate');

        $attendances = $query->paginate(15)->withQueryString();
        $classes = Classes::orderBy('name')->get();
        $students = Student::with('user')->get();
        $programs = Student::whereNotNull('program')->where('program', '!=', '')->distinct()->pluck('program')->sort()->values();

        return view('admin.attendances.index', compact('attendances', 'classes', 'students', 'programs', 'stats'));
    }

    public function create(Request $request)
    {
        $classes = Classes::with('students.user')->where('status', 'aktif')->orderBy('name')->get();
        $schedules = Schedule::with('class')->orderBy('date', 'desc')->get();
        $selectedClassId = $request->class_id;
        $selectedScheduleId = $request->schedule_id;

        $studentsInClass = collect();
        if ($selectedClassId) {
            $studentsInClass = Student::with('user')->where('class_id', $selectedClassId)->where('status', 'aktif')->get();
        }

        $allStudents = Student::with('user')->orderBy('student_number')->get();
        $locations = \App\Models\AttendanceLocation::where('is_active', true)->get();
        $primaryLocation = \App\Models\AttendanceLocation::getPrimary();

        return view('admin.attendances.create', compact(
            'classes', 
            'schedules', 
            'selectedClassId', 
            'selectedScheduleId', 
            'studentsInClass', 
            'allStudents',
            'locations',
            'primaryLocation'
        ));
    }

    public function store(Request $request)
    {
        // Check if bulk input (attendance array by student_id)
        if ($request->has('students_attendance') && is_array($request->students_attendance)) {
            $request->validate([
                'date' => ['required', 'date'],
                'schedule_id' => ['nullable', 'exists:schedules,id'],
                'students_attendance' => ['required', 'array'],
            ]);

            DB::transaction(function () use ($request) {
                foreach ($request->students_attendance as $studentId => $data) {
                    Attendance::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'schedule_id' => $request->schedule_id ?: null,
                            'date' => $request->date,
                        ],
                        [
                            'status' => $data['status'] ?? 'hadir',
                            'note' => $data['note'] ?? null,
                        ]
                    );
                }
            });

            return redirect()->route('admin.attendances.index')->with('success', 'Presensi kelas berhasil disimpan.');
        }

        // Single attendance entry
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'schedule_id' => ['nullable', 'exists:schedules,id'],
            'date' => ['required', 'date'],
            'check_in_time' => ['nullable'],
            'check_out_time' => ['nullable'],
            'status' => ['required', 'in:hadir,terlambat,izin,sakit,alpa'],
            'note' => ['nullable', 'string'],
            'check_in_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'check_in_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'attendance_location_id' => ['nullable', 'exists:attendance_locations,id'],
        ]);

        $distance = null;
        $locationId = $validated['attendance_location_id'] ?? null;
        if (!empty($validated['check_in_lat']) && !empty($validated['check_in_lng'])) {
            $userLat = (float) $validated['check_in_lat'];
            $userLng = (float) $validated['check_in_lng'];

            if (!empty($locationId)) {
                $selectedLoc = \App\Models\AttendanceLocation::find($locationId);
                if ($selectedLoc) {
                    $distance = $selectedLoc->distanceFrom($userLat, $userLng);
                }
            } else {
                $nearest = \App\Models\AttendanceLocation::findNearestActive($userLat, $userLng);
                if ($nearest) {
                    $distance = $nearest['distance'];
                    $locationId = $nearest['location']->id;
                }
            }
        }

        Attendance::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'schedule_id' => $validated['schedule_id'] ?? null,
                'date' => $validated['date'],
            ],
            [
                'attendance_location_id' => $locationId,
                'check_in_time' => $validated['check_in_time'] ?? null,
                'check_out_time' => $validated['check_out_time'] ?? null,
                'check_in_lat' => $validated['check_in_lat'] ?? null,
                'check_in_lng' => $validated['check_in_lng'] ?? null,
                'check_in_distance' => $distance,
                'status' => $validated['status'],
                'note' => $validated['note'] ?? null,
            ]
        );

        return redirect()->route('admin.attendances.index')->with('success', 'Data absensi berhasil dicatat.');
    }

    public function edit(Attendance $attendance)
    {
        $attendance->load(['student.user', 'schedule.class', 'attendanceLocation']);
        $locations = \App\Models\AttendanceLocation::where('is_active', true)->get();
        $primaryLocation = \App\Models\AttendanceLocation::getPrimary();
        return view('admin.attendances.edit', compact('attendance', 'locations', 'primaryLocation'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in_time' => ['nullable'],
            'check_out_time' => ['nullable'],
            'status' => ['required', 'in:hadir,terlambat,izin,sakit,alpa'],
            'note' => ['nullable', 'string'],
            'check_in_lat' => ['nullable', 'numeric', 'between:-90,90'],
            'check_in_lng' => ['nullable', 'numeric', 'between:-180,180'],
            'attendance_location_id' => ['nullable', 'exists:attendance_locations,id'],
        ]);

        if (!empty($validated['check_in_lat']) && !empty($validated['check_in_lng'])) {
            $userLat = (float) $validated['check_in_lat'];
            $userLng = (float) $validated['check_in_lng'];

            if (!empty($validated['attendance_location_id'])) {
                $selectedLoc = \App\Models\AttendanceLocation::find($validated['attendance_location_id']);
                if ($selectedLoc) {
                    $validated['check_in_distance'] = $selectedLoc->distanceFrom($userLat, $userLng);
                }
            } else {
                $nearest = \App\Models\AttendanceLocation::findNearestActive($userLat, $userLng);
                if ($nearest) {
                    $validated['check_in_distance'] = $nearest['distance'];
                    $validated['attendance_location_id'] = $nearest['location']->id;
                }
            }
        } else {
            $validated['check_in_lat'] = null;
            $validated['check_in_lng'] = null;
            $validated['check_in_distance'] = null;
            $validated['attendance_location_id'] = null;
        }

        $attendance->update($validated);

        return redirect()->route('admin.attendances.index')->with('success', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendances.index')->with('success', 'Data absensi berhasil dihapus.');
    }

    public function rekap(Request $request)
    {
        $classes = Classes::orderBy('name')->get();
        $selectedClassId = $request->class_id;
        $selectedProgram = $request->program;

        $studentsQuery = Student::with(['user', 'class', 'attendances'])->orderBy('student_number');

        if ($selectedClassId) {
            $studentsQuery->where('class_id', $selectedClassId);
        }

        if ($selectedProgram) {
            $studentsQuery->where('program', $selectedProgram);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('school_origin', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $students = $studentsQuery->get()->map(function ($student) {
            $stats = $student->attendanceStats();
            return [
                'student' => $student,
                'stats' => $stats,
            ];
        });

        $programs = Student::whereNotNull('program')->where('program', '!=', '')->distinct()->pluck('program')->sort()->values();

        return view('admin.attendances.rekap', compact('classes', 'selectedClassId', 'selectedProgram', 'programs', 'students'));
    }

    public function printStudentAttendance(Request $request, Student $student)
    {
        $student->load(['user', 'class']);

        $attendancesQuery = $student->attendances()->with('schedule')->orderBy('date', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $attendancesQuery->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $attendancesQuery->where('status', $request->status);
        }

        $attendances = $attendancesQuery->get();
        $stats = $student->attendanceStats();

        return view('admin.attendances.print-student', compact('student', 'attendances', 'stats'));
    }

    public function runAutoAlpa(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $activeStudents = Student::with('user')->where('status', 'aktif')->get();
        $countCreated = 0;
        $countSkipped = 0;

        foreach ($activeStudents as $student) {
            $attendance = Attendance::where('student_id', $student->id)
                ->whereDate('date', $date)
                ->first();

            if ($attendance) {
                $countSkipped++;
                continue;
            }

            $pendingPermission = Permission::where('student_id', $student->id)
                ->whereDate('date', $date)
                ->where('status', 'menunggu')
                ->first();

            $note = $pendingPermission 
                ? "Otomatis Alpa oleh Sistem (Terdapat pengajuan {$pendingPermission->type} yang masih menunggu verifikasi admin)"
                : "Otomatis Alpa oleh Sistem (Tidak melakukan presensi harian hingga batas pukul 17:00 WIB)";

            Attendance::create([
                'student_id' => $student->id,
                'date' => $date,
                'status' => 'alpa',
                'note' => $note,
            ]);

            $countCreated++;
        }

        $formattedDate = Carbon::parse($date)->translatedFormat('d F Y');
        if ($countCreated > 0) {
            $msg = "Auto-Alpa berhasil dijalankan untuk tanggal {$formattedDate}: {$countCreated} siswa dicatat Alpa ({$countSkipped} siswa sudah memiliki status presensi).";
        } else {
            $msg = "Auto-Alpa selesai untuk tanggal {$formattedDate}: Seluruh {$countSkipped} siswa aktif sudah memiliki status presensi (tidak ada yang alpa).";
        }

        return redirect()->back()->with('success', $msg);
    }
}
