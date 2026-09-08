@extends('layouts.siswa', ['title' => 'Nilai Saya', 'header' => 'Nilai Akademik Saya'])

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Transkrip Nilai Akademik</h2>
        <p class="text-xs text-slate-500">Hasil evaluasi belajar dan pencapaian materi pelatihan Anda.</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5 shadow-xs">
            <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Rata-Rata Nilai</p>
            <p class="mt-2 text-3xl font-black text-emerald-900">{{ $averageScore }}</p>
            <p class="text-[11px] text-emerald-700 mt-1 font-medium">Dari {{ $grades->count() }} modul materi yang telah dinilai</p>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nilai Tertinggi</p>
            <p class="mt-2 text-3xl font-black text-slate-800">{{ $highestScore }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Pencapaian skor maksimal</p>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Modul Dinilai</p>
            <p class="mt-2 text-3xl font-black text-slate-800">{{ $grades->count() }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Tugas, kuis, dan ujian praktik</p>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Rincian Nilai per Modul</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Mata Pelajaran / Materi</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4 text-center">Skor Nilai</th>
                        <th class="px-6 py-4">Keterangan & Feedback</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($grades as $grade)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $grade->subject }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $grade->class->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-xl font-black text-sm px-3.5 py-1.5
                                    {{ $grade->score >= 85 ? 'bg-emerald-50 text-emerald-700' : ($grade->score >= 70 ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ $grade->score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-sm">
                                {{ $grade->description ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                Belum ada nilai yang diinput oleh mentor/admin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
