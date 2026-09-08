<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Profil siswa belum terhubung dengan akun ini.');
        }

        $stats = $student->attendanceStats();

        $upcomingSchedules = collect();
        if ($student->class_id) {
            $upcomingSchedules = Schedule::where('class_id', $student->class_id)
                ->whereDate('date', '>=', Carbon::today())
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc')
                ->take(4)
                ->get();
        }

        $recentAnnouncements = Announcement::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('siswa.dashboard', compact('student', 'stats', 'upcomingSchedules', 'recentAnnouncements'));
    }
}
