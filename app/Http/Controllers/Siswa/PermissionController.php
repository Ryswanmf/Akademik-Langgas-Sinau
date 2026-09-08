<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $permissions = Permission::where('student_id', $student->id)
            ->latest('date')
            ->paginate(10);

        return view('siswa.permissions', compact('student', 'permissions'));
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        $validated = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', 'string', 'max:50'],
            'reason' => ['required', 'string'],
            'evidence' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:3072'],
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('evidence', 'public');
        }

        Permission::create([
            'student_id' => $student->id,
            'date' => $validated['date'],
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'evidence' => $evidencePath,
            'status' => 'menunggu',
            'admin_note' => null,
        ]);

        return back()->with('success', 'Pengajuan izin Anda berhasil dikirim dan sedang menunggu peninjauan admin.');
    }
}
