<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with('class')->latest('date');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('room', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $schedules = $query->paginate(10)->withQueryString();
        $classes = Classes::orderBy('name')->get();

        return view('admin.schedules.index', compact('schedules', 'classes'));
    }

    public function create()
    {
        $classes = Classes::where('status', 'aktif')->orderBy('name')->get();
        return view('admin.schedules.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => ['nullable'],
            'class_name' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'room' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
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
            $classId = Classes::firstOrCreate(['name' => 'Pelatihan Umum'], ['program' => 'Umum', 'status' => 'aktif'])->id;
        }

        Schedule::create([
            'class_id' => $classId,
            'title' => $validated['title'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room' => $validated['room'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal baru berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule)
    {
        $classes = Classes::orderBy('name')->get();
        return view('admin.schedules.edit', compact('schedule', 'classes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'class_id' => ['nullable'],
            'class_name' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'room' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
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
            $classId = $schedule->class_id;
        }

        $schedule->update([
            'class_id' => $classId,
            'title' => $validated['title'],
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'room' => $validated['room'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
