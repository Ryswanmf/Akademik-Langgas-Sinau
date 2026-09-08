<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $query = Grade::with(['student.user', 'class'])->latest('id');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('subject', 'like', "%{$search}%");
        }

        $grades = $query->paginate(10)->withQueryString();
        $classes = Classes::orderBy('name')->get();
        $students = Student::with('user')->get();

        return view('admin.grades.index', compact('grades', 'classes', 'students'));
    }

    public function create(Request $request)
    {
        $classes = Classes::orderBy('name')->get();
        $students = Student::with(['user', 'class'])->orderBy('id')->get();
        $selectedStudentId = $request->student_id;

        return view('admin.grades.create', compact('classes', 'students', 'selectedStudentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'class_id' => ['nullable'],
            'class_name' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $classId = null;
        if ($request->filled('class_name')) {
            $cls = Classes::firstOrCreate(
                ['name' => trim($request->class_name)],
                ['program' => 'Pelatihan LKP', 'status' => 'aktif']
            );
            $classId = $cls->id;
        } elseif ($request->filled('class_id') && is_numeric($request->class_id)) {
            $classId = $request->class_id;
        } else {
            $student = Student::find($request->student_id);
            $classId = $student?->class_id ?? Classes::firstOrCreate(['name' => 'Pelatihan LKP'], ['program' => 'Umum', 'status' => 'aktif'])->id;
        }

        Grade::create([
            'student_id' => $validated['student_id'],
            'class_id' => $classId,
            'subject' => $validated['subject'],
            'score' => $validated['score'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.grades.index')->with('success', 'Nilai siswa berhasil disimpan.');
    }

    public function edit(Grade $grade)
    {
        $classes = Classes::orderBy('name')->get();
        $students = Student::with(['user', 'class'])->get();

        return view('admin.grades.edit', compact('grade', 'classes', 'students'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'class_id' => ['nullable'],
            'class_name' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'score' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $classId = null;
        if ($request->filled('class_name')) {
            $cls = Classes::firstOrCreate(
                ['name' => trim($request->class_name)],
                ['program' => 'Pelatihan LKP', 'status' => 'aktif']
            );
            $classId = $cls->id;
        } elseif ($request->filled('class_id') && is_numeric($request->class_id)) {
            $classId = $request->class_id;
        } else {
            $classId = $grade->class_id;
        }

        $grade->update([
            'student_id' => $validated['student_id'],
            'class_id' => $classId,
            'subject' => $validated['subject'],
            'score' => $validated['score'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.grades.index')->with('success', 'Data nilai berhasil diperbarui.');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('admin.grades.index')->with('success', 'Data nilai berhasil dihapus.');
    }
}
