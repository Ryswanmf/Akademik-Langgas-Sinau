<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $grades = Grade::with('class')
            ->where('student_id', $student->id)
            ->latest('id')
            ->get();

        $averageScore = $grades->count() > 0 ? round($grades->avg('score'), 2) : 0;
        $highestScore = $grades->count() > 0 ? $grades->max('score') : 0;

        return view('siswa.grades', compact('student', 'grades', 'averageScore', 'highestScore'));
    }
}
