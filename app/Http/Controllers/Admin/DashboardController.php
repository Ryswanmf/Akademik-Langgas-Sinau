<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Certificate;
use App\Models\Announcement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'aktif')->count();
        $totalClasses = Classes::count();
        
        $todayAttendances = Attendance::whereDate('date', $today)->get();
        $todayPresent = $todayAttendances->where('status', 'hadir')->count();
        
        $pendingPermissions = Permission::where('status', 'menunggu')->count();
        $totalCertificates = Certificate::count();

        // Attendance stats for visual chart
        $totalAttendances = Attendance::count();
        $hadirCount = Attendance::where('status', 'hadir')->count();
        $izinCount = Attendance::where('status', 'izin')->count();
        $sakitCount = Attendance::where('status', 'sakit')->count();
        $alpaCount = Attendance::where('status', 'alpa')->count();

        $attendanceStats = [
            'total' => $totalAttendances,
            'hadir' => $hadirCount,
            'izin' => $izinCount,
            'sakit' => $sakitCount,
            'alpa' => $alpaCount,
            'hadir_percentage' => $totalAttendances > 0 ? round(($hadirCount / $totalAttendances) * 100, 1) : 0,
            'izin_percentage' => $totalAttendances > 0 ? round(($izinCount / $totalAttendances) * 100, 1) : 0,
            'sakit_percentage' => $totalAttendances > 0 ? round(($sakitCount / $totalAttendances) * 100, 1) : 0,
            'alpa_percentage' => $totalAttendances > 0 ? round(($alpaCount / $totalAttendances) * 100, 1) : 0,
        ];

        $recentAttendances = Attendance::with(['student.user', 'schedule.class'])
            ->latest('id')
            ->take(5)
            ->get();

        $recentStudents = Student::with(['user', 'class'])
            ->latest('id')
            ->take(5)
            ->get();

        $recentAnnouncements = Announcement::latest('published_at')
            ->take(3)
            ->get();

        $primaryLocation = \App\Models\AttendanceLocation::getPrimary();

        return view('admin.dashboard', compact(
            'totalStudents',
            'activeStudents',
            'totalClasses',
            'todayPresent',
            'pendingPermissions',
            'totalCertificates',
            'attendanceStats',
            'recentAttendances',
            'recentStudents',
            'recentAnnouncements',
            'primaryLocation'
        ));
    }
}
