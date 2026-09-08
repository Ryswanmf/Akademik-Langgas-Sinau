<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['student.user', 'student.class'])->latest('issued_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%")
                  ->orWhere('program', 'like', "%{$search}%")
                  ->orWhereHas('student.user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $totalCount = Certificate::count();
        $publishedCount = Certificate::where('is_published', true)->count();
        $draftCount = Certificate::where('is_published', false)->count();

        $certificates = $query->paginate(10)->withQueryString();

        return view('admin.certificates.index', compact('certificates', 'totalCount', 'publishedCount', 'draftCount'));
    }

    public function create(Request $request)
    {
        $students = Student::with(['user', 'class'])->orderBy('id')->get()->map(function ($s) {
            $stats = $s->attendanceStats();
            $s->attendance_rate = $stats['rate'];
            return $s;
        });

        $selectedStudentId = $request->student_id;
        return view('admin.certificates.create', compact('students', 'selectedStudentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:100', 'unique:certificates,certificate_number'],
            'program' => ['required', 'string', 'max:255'],
            'mentor_name' => ['nullable', 'string', 'max:255'],
            'leader_name' => ['nullable', 'string', 'max:255'],
            'issued_date' => ['required', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'is_published' => ['nullable'],
            'score_discipline' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_initiative' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_teamwork' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_responsibility' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_attitude' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_attendance' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessment_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'student_id.required' => 'Pilih siswa penerima nilai & sertifikat.',
            'name.required' => 'Nama sertifikat / kompetensi wajib diisi.',
            'certificate_number.required' => 'Nomor sertifikat wajib diisi.',
            'certificate_number.unique' => 'Nomor sertifikat sudah terdaftar, gunakan nomor unik lainnya.',
            'program.required' => 'Program keahlian wajib diisi.',
            'issued_date.required' => 'Tanggal penerbitan wajib diisi.',
            'score_discipline.numeric' => 'Nilai Disiplin harus berupa angka 0 - 100.',
            'score_initiative.numeric' => 'Nilai Inisiatif & Kreatifitas harus berupa angka 0 - 100.',
            'score_teamwork.numeric' => 'Nilai Kerja sama harus berupa angka 0 - 100.',
            'score_responsibility.numeric' => 'Nilai Tanggung Jawab harus berupa angka 0 - 100.',
            'score_attitude.numeric' => 'Nilai Sikap harus berupa angka 0 - 100.',
            'score_attendance.numeric' => 'Nilai Kehadiran harus berupa angka 0 - 100.',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('certificates', 'public');
        }

        $scores = array_filter([
            $validated['score_discipline'] ?? null,
            $validated['score_initiative'] ?? null,
            $validated['score_teamwork'] ?? null,
            $validated['score_responsibility'] ?? null,
            $validated['score_attitude'] ?? null,
            $validated['score_attendance'] ?? null,
        ], fn ($val) => !is_null($val));

        $finalScore = !empty($scores) ? round(array_sum($scores) / count($scores), 2) : null;
        $gradePredicate = Certificate::determinePredicate($finalScore);
        $isPublished = $request->boolean('is_published');

        Certificate::create([
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'certificate_number' => $validated['certificate_number'],
            'program' => $validated['program'],
            'mentor_name' => $validated['mentor_name'] ?? null,
            'leader_name' => $validated['leader_name'] ?? null,
            'issued_date' => $validated['issued_date'],
            'file' => $filePath ?? '',
            'is_published' => $isPublished,
            'score_discipline' => $validated['score_discipline'] ?? null,
            'score_initiative' => $validated['score_initiative'] ?? null,
            'score_teamwork' => $validated['score_teamwork'] ?? null,
            'score_responsibility' => $validated['score_responsibility'] ?? null,
            'score_attitude' => $validated['score_attitude'] ?? null,
            'score_attendance' => $validated['score_attendance'] ?? null,
            'final_score' => $finalScore,
            'grade_predicate' => $gradePredicate,
            'assessment_notes' => $validated['assessment_notes'] ?? null,
        ]);

        $msg = $isPublished 
            ? 'Nilai & Sertifikat berhasil diterbitkan dan dipublikasikan ke siswa.'
            : 'Nilai & Sertifikat berhasil disimpan sebagai draft (belum tampil di siswa).';

        return redirect()->route('admin.certificates.index')->with('success', $msg);
    }

    public function edit(Certificate $certificate)
    {
        $students = Student::with(['user', 'class'])->get()->map(function ($s) {
            $stats = $s->attendanceStats();
            $s->attendance_rate = $stats['rate'];
            return $s;
        });

        return view('admin.certificates.edit', compact('certificate', 'students'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'name' => ['required', 'string', 'max:255'],
            'certificate_number' => ['required', 'string', 'max:100', Rule::unique('certificates', 'certificate_number')->ignore($certificate->id)],
            'program' => ['required', 'string', 'max:255'],
            'mentor_name' => ['nullable', 'string', 'max:255'],
            'leader_name' => ['nullable', 'string', 'max:255'],
            'issued_date' => ['required', 'date'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'is_published' => ['nullable'],
            'score_discipline' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_initiative' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_teamwork' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_responsibility' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_attitude' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_attendance' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'assessment_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'student_id.required' => 'Pilih siswa penerima nilai & sertifikat.',
            'name.required' => 'Nama sertifikat / kompetensi wajib diisi.',
            'certificate_number.required' => 'Nomor sertifikat wajib diisi.',
            'certificate_number.unique' => 'Nomor sertifikat sudah terdaftar, gunakan nomor unik lainnya.',
            'program.required' => 'Program keahlian wajib diisi.',
            'issued_date.required' => 'Tanggal penerbitan wajib diisi.',
            'score_discipline.numeric' => 'Nilai Disiplin harus berupa angka 0 - 100.',
            'score_initiative.numeric' => 'Nilai Inisiatif & Kreatifitas harus berupa angka 0 - 100.',
            'score_teamwork.numeric' => 'Nilai Kerja sama harus berupa angka 0 - 100.',
            'score_responsibility.numeric' => 'Nilai Tanggung Jawab harus berupa angka 0 - 100.',
            'score_attitude.numeric' => 'Nilai Sikap harus berupa angka 0 - 100.',
            'score_attendance.numeric' => 'Nilai Kehadiran harus berupa angka 0 - 100.',
        ]);

        $scores = array_filter([
            $validated['score_discipline'] ?? null,
            $validated['score_initiative'] ?? null,
            $validated['score_teamwork'] ?? null,
            $validated['score_responsibility'] ?? null,
            $validated['score_attitude'] ?? null,
            $validated['score_attendance'] ?? null,
        ], fn ($val) => !is_null($val));

        $finalScore = !empty($scores) ? round(array_sum($scores) / count($scores), 2) : null;
        $gradePredicate = Certificate::determinePredicate($finalScore);
        $isPublished = $request->boolean('is_published');

        $data = [
            'student_id' => $validated['student_id'],
            'name' => $validated['name'],
            'certificate_number' => $validated['certificate_number'],
            'program' => $validated['program'],
            'mentor_name' => $validated['mentor_name'] ?? null,
            'leader_name' => $validated['leader_name'] ?? null,
            'issued_date' => $validated['issued_date'],
            'is_published' => $isPublished,
            'score_discipline' => $validated['score_discipline'] ?? null,
            'score_initiative' => $validated['score_initiative'] ?? null,
            'score_teamwork' => $validated['score_teamwork'] ?? null,
            'score_responsibility' => $validated['score_responsibility'] ?? null,
            'score_attitude' => $validated['score_attitude'] ?? null,
            'score_attendance' => $validated['score_attendance'] ?? null,
            'final_score' => $finalScore,
            'grade_predicate' => $gradePredicate,
            'assessment_notes' => $validated['assessment_notes'] ?? null,
        ];

        if ($request->hasFile('file')) {
            if ($certificate->file && Storage::disk('public')->exists($certificate->file)) {
                Storage::disk('public')->delete($certificate->file);
            }
            $data['file'] = $request->file('file')->store('certificates', 'public');
        }

        $certificate->update($data);

        return redirect()->route('admin.certificates.index')->with('success', 'Data Nilai & Sertifikat berhasil diperbarui.');
    }

    public function togglePublish(Certificate $certificate)
    {
        $certificate->is_published = !$certificate->is_published;
        $certificate->save();

        $studentName = $certificate->student->user->name ?? 'Siswa';
        $msg = $certificate->is_published
            ? "Nilai & Sertifikat untuk {$studentName} berhasil DIPUBLIKASIKAN. Siswa sekarang dapat melihatnya di portal."
            : "Nilai & Sertifikat untuk {$studentName} berhasil DIUBAH MENJADI DRAFT (disembunyikan dari siswa).";

        return back()->with('success', $msg);
    }

    public function showCertificate(Certificate $certificate)
    {
        $certificate->load(['student.user', 'student.class']);
        return view('admin.certificates.certificate', compact('certificate'));
    }

    public function print(Certificate $certificate)
    {
        $certificate->load(['student.user', 'student.class']);
        return view('admin.certificates.print', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        if (!$certificate->file || !Storage::disk('public')->exists($certificate->file)) {
            return back()->with('error', 'File fisik sertifikat tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($certificate->file, $certificate->certificate_number . '-' . $certificate->name . '.pdf');
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->file && Storage::disk('public')->exists($certificate->file)) {
            Storage::disk('public')->delete($certificate->file);
        }

        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('success', 'Data Nilai & Sertifikat berhasil dihapus.');
    }
}
