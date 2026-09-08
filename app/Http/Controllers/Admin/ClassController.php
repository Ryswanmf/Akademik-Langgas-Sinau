<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = Classes::withCount(['students', 'schedules'])->latest('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('program', 'like', "%{$search}%");
            });
        }

        $classes = $query->paginate(10)->withQueryString();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        Classes::create($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function show(Classes $class)
    {
        $class->load([
            'students.user', 
            'schedules' => function ($q) {
                $q->orderBy('date', 'desc');
            }
        ]);

        return view('admin.classes.show', compact('class'));
    }

    public function edit(Classes $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Classes $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
