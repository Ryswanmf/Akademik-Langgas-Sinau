<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student->class_id) {
            $schedules = collect();
            return view('siswa.schedules', compact('student', 'schedules'));
        }

        $query = Schedule::where('class_id', $student->class_id)->orderBy('date', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('room', 'like', "%{$search}%");
            });
        }

        $schedules = $query->paginate(10)->withQueryString();

        return view('siswa.schedules', compact('student', 'schedules'));
    }
}
