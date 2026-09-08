<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $student->load(['user', 'class']);

        return view('siswa.profile', compact('student'));
    }

    public function update(Request $request)
    {
        $student = auth()->user()->student;

        $validated = $request->validate([
            'program' => ['required', 'string', 'max:255'],
            'school_origin' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'school_origin.required' => 'Asal instansi / sekolah wajib diisi.',
            'program.required' => 'Program keahlian wajib diisi.',
        ]);

        $data = [
            'program' => $validated['program'],
            'school_origin' => $validated['school_origin'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $student->update($data);

        return back()->with('success', 'Biodata profil Anda berhasil diperbarui.');
    }
}
