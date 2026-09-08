<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        if (!$student) {
            abort(403, 'Profil siswa belum terhubung dengan akun ini.');
        }

        // Nilai & Sertifikat HANYA muncul ketika admin sudah publish
        $certificates = Certificate::where('student_id', $student->id)
            ->published()
            ->latest('issued_date')
            ->get();

        return view('siswa.certificates', compact('student', 'certificates'));
    }

    public function download(Certificate $certificate)
    {
        $student = auth()->user()->student;

        // Security check: Only own certificates can be downloaded
        if ($certificate->student_id !== $student->id) {
            abort(403, 'Akses tidak sah. Anda hanya dapat mengunduh sertifikat milik Anda sendiri.');
        }

        // Security check: Cannot download if not published yet
        if (!$certificate->is_published) {
            abort(403, 'Nilai & Sertifikat ini belum dipublikasikan oleh Administrator.');
        }

        if (!$certificate->file || !Storage::disk('public')->exists($certificate->file)) {
            return back()->with('error', 'File fisik sertifikat tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($certificate->file, $certificate->certificate_number . '-' . $certificate->name . '.pdf');
    }

    public function showCertificate(Certificate $certificate)
    {
        $student = auth()->user()->student;

        if ($certificate->student_id !== $student->id) {
            abort(403, 'Akses tidak sah. Anda hanya dapat melihat sertifikat milik Anda sendiri.');
        }

        if (!$certificate->is_published) {
            abort(403, 'Nilai & Sertifikat ini belum dipublikasikan oleh Administrator.');
        }

        $certificate->load(['student.user', 'student.class']);
        return view('admin.certificates.certificate', compact('certificate'));
    }

    public function print(Certificate $certificate)
    {
        $student = auth()->user()->student;

        if ($certificate->student_id !== $student->id) {
            abort(403, 'Akses tidak sah. Anda hanya dapat mencetak sertifikat milik Anda sendiri.');
        }

        if (!$certificate->is_published) {
            abort(403, 'Nilai & Sertifikat ini belum dipublikasikan oleh Administrator.');
        }

        $certificate->load(['student.user', 'student.class']);
        return view('admin.certificates.print', compact('certificate'));
    }
}
