<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'class'])->latest('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(10)->withQueryString();
        $classes = Classes::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = Classes::where('status', 'aktif')->orderBy('name')->get();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'student_number' => ['required', 'string', 'max:50', 'unique:students,student_number'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'class_id' => ['nullable'],
            'class_name' => ['nullable', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'school_origin' => ['required', 'string', 'max:255'],
            'entry_date' => ['required', 'date'],
            'status' => ['required', 'in:aktif,nonaktif,lulus'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $classId = null;
        if ($request->filled('class_name')) {
            $cls = Classes::firstOrCreate(
                ['name' => trim($request->class_name)],
                [
                    'program' => $validated['program'] ?? 'Pelatihan LKP',
                    'status' => 'aktif',
                ]
            );
            $classId = $cls->id;
        } elseif ($request->filled('class_id') && is_numeric($request->class_id)) {
            $classId = $request->class_id;
        }

        DB::transaction(function () use ($request, $validated, $classId) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
            ]);

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            Student::create([
                'user_id' => $user->id,
                'class_id' => $classId,
                'student_number' => $validated['student_number'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'photo' => $photoPath,
                'program' => $validated['program'],
                'school_origin' => $validated['school_origin'],
                'entry_date' => $validated['entry_date'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        $student->load([
            'user', 
            'class', 
            'attendances.schedule', 
            'grades.class', 
            'certificates', 
            'permissions'
        ]);

        $stats = $student->attendanceStats();

        return view('admin.students.show', compact('student', 'stats'));
    }

    public function edit(Student $student)
    {
        $student->load(['user', 'class']);
        $classes = Classes::orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
            'student_number' => ['required', 'string', 'max:50', Rule::unique('students', 'student_number')->ignore($student->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'class_id' => ['nullable'],
            'class_name' => ['nullable', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'school_origin' => ['required', 'string', 'max:255'],
            'entry_date' => ['required', 'date'],
            'status' => ['required', 'in:aktif,nonaktif,lulus'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $classId = null;
        if ($request->filled('class_name')) {
            $cls = Classes::firstOrCreate(
                ['name' => trim($request->class_name)],
                [
                    'program' => $validated['program'] ?? 'Pelatihan LKP',
                    'status' => 'aktif',
                ]
            );
            $classId = $cls->id;
        } elseif ($request->filled('class_id') && is_numeric($request->class_id)) {
            $classId = $request->class_id;
        }

        DB::transaction(function () use ($request, $validated, $student, $classId) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $student->user->update($userData);

            $studentData = [
                'class_id' => $classId,
                'student_number' => $validated['student_number'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'program' => $validated['program'],
                'school_origin' => $validated['school_origin'],
                'entry_date' => $validated['entry_date'],
                'status' => $validated['status'],
            ];

            if ($request->hasFile('photo')) {
                if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                    Storage::disk('public')->delete($student->photo);
                }
                $studentData['photo'] = $request->file('photo')->store('photos', 'public');
            }

            $student->update($studentData);
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Student $student)
    {
        $request->validate([
            'status' => ['required', 'in:aktif,nonaktif,lulus'],
        ]);

        $student->update(['status' => $request->status]);

        return back()->with('success', 'Status siswa berhasil diubah menjadi ' . ucfirst($request->status) . '.');
    }

    public function destroy(Student $student)
    {
        DB::transaction(function () use ($student) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $user = $student->user;
            $student->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.students.index')->with('success', 'Data siswa dan akun berhasil dihapus.');
    }
}
