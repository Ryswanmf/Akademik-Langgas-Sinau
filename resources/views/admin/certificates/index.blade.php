@extends('layouts.admin', ['title' => 'Nilai & Sertifikat Siswa', 'header' => 'Kelola Nilai & Sertifikat'])

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Nilai & Sertifikat Siswa</h2>
            <p class="text-xs text-slate-500">Kelola evaluasi 6 komponen penilaian (Disiplin, Inisiatif, Kerja sama, Tanggung Jawab, Sikap, Kehadiran) dan publikasi sertifikat ke siswa.</p>
        </div>
        <a href="{{ route('admin.certificates.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Terbitkan Nilai & Sertifikat
        </a>
    </div>

    <!-- Metric Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.certificates.index') }}" class="rounded-2xl border p-4 transition-all {{ !request('status') ? 'border-indigo-300 bg-indigo-50/50 shadow-xs' : 'border-slate-200/80 bg-white hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Terdaftar</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs">
                    {{ $totalCount }}
                </span>
            </div>
            <p class="mt-2 text-2xl font-black text-slate-800">{{ $totalCount }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5">Seluruh arsip penilaian & sertifikat</p>
        </a>

        <a href="{{ route('admin.certificates.index', ['status' => 'published']) }}" class="rounded-2xl border p-4 transition-all {{ request('status') === 'published' ? 'border-emerald-300 bg-emerald-50/50 shadow-xs' : 'border-slate-200/80 bg-white hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Dipublikasikan (Tampil di Siswa)</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 font-bold text-xs">
                    {{ $publishedCount }}
                </span>
            </div>
            <p class="mt-2 text-2xl font-black text-emerald-700">{{ $publishedCount }}</p>
            <p class="text-[11px] text-emerald-600 mt-0.5">Siswa dapat melihat nilai & unduh sertifikat</p>
        </a>

        <a href="{{ route('admin.certificates.index', ['status' => 'draft']) }}" class="rounded-2xl border p-4 transition-all {{ request('status') === 'draft' ? 'border-amber-300 bg-amber-50/50 shadow-xs' : 'border-slate-200/80 bg-white hover:border-slate-300' }}">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Draft (Belum Publish)</span>
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700 font-bold text-xs">
                    {{ $draftCount }}
                </span>
            </div>
            <p class="mt-2 text-2xl font-black text-amber-700">{{ $draftCount }}</p>
            <p class="text-[11px] text-amber-600 mt-0.5">Disembunyikan dari halaman siswa</p>
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <form method="GET" action="{{ route('admin.certificates.index') }}" class="flex gap-2 w-full sm:max-w-md">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari nomor, sertifikat, atau nama siswa..." 
                class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                Cari
            </button>
            @if (request('search') || request('status'))
                <a href="{{ route('admin.certificates.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>

        <div class="text-[11px] text-slate-500">
            Menampilkan <strong>{{ $certificates->total() }}</strong> data
        </div>
    </div>

    <!-- Certificates Table -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Sertifikat</th>
                        <th class="px-6 py-4">Siswa Penerima</th>
                        <th class="px-6 py-4">Nilai Akhir & Predikat</th>
                        <th class="px-6 py-4">Status Publikasi</th>
                        <th class="px-6 py-4">Berkas</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($certificates as $cert)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Sertifikat & Nomor -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-xl {{ $cert->is_published ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-400' }} shrink-0">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $cert->name }}</p>
                                        <p class="text-[11px] font-mono text-indigo-600">{{ $cert->certificate_number }}</p>
                                        <p class="text-[10px] text-slate-400">Terbit: {{ $cert->issued_date ? $cert->issued_date->translatedFormat('d M Y') : '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Siswa Penerima & Program -->
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $cert->student->user->name ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">NIS: {{ $cert->student->student_number ?? '-' }}</p>
                                <p class="text-[11px] text-indigo-700 font-semibold">{{ $cert->program }}</p>
                                @if($cert->student->school_origin)
                                    <p class="text-[10px] text-slate-500">{{ $cert->student->school_origin }}</p>
                                @endif
                            </td>

                            <!-- Nilai Akhir & Rincian 6 Kriteria -->
                            <td class="px-6 py-4">
                                @if ($cert->final_score !== null)
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-base font-black text-slate-800">{{ number_format($cert->final_score, 1) }}</span>
                                            <span class="rounded-md bg-purple-50 px-2 py-0.5 text-[10px] font-bold text-purple-700">
                                                {{ $cert->grade_predicate ?: 'Lulus' }}
                                            </span>
                                        </div>
                                        <div class="flex flex-wrap gap-1 text-[9px] text-slate-500">
                                            <span title="Disiplin" class="bg-slate-100 px-1.5 py-0.5 rounded">Disiplin: {{ $cert->score_discipline ?? '-' }}</span>
                                            <span title="Inisiatif & Kreatifitas" class="bg-slate-100 px-1.5 py-0.5 rounded">Inisiatif: {{ $cert->score_initiative ?? '-' }}</span>
                                            <span title="Kerja sama" class="bg-slate-100 px-1.5 py-0.5 rounded">Kerja Sama: {{ $cert->score_teamwork ?? '-' }}</span>
                                            <span title="Tanggung Jawab" class="bg-slate-100 px-1.5 py-0.5 rounded">T. Jawab: {{ $cert->score_responsibility ?? '-' }}</span>
                                            <span title="Sikap" class="bg-slate-100 px-1.5 py-0.5 rounded">Sikap: {{ $cert->score_attitude ?? '-' }}</span>
                                            <span title="Kehadiran" class="bg-slate-100 px-1.5 py-0.5 rounded">Kehadiran: {{ $cert->score_attendance ?? '-' }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">Belum dinilai</span>
                                @endif
                            </td>

                            <!-- Status Publikasi (Muncul di Siswa / Belum) -->
                            <td class="px-6 py-4">
                                <div class="space-y-1.5">
                                    @if ($cert->is_published)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Dipublikasikan
                                        </span>
                                        <form action="{{ route('admin.certificates.toggle-publish', $cert) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-[10px] text-slate-400 hover:text-amber-600 underline cursor-pointer">
                                                Tarik ke Draft
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                            Draft (Disembunyikan)
                                        </span>
                                        <form action="{{ route('admin.certificates.toggle-publish', $cert) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 underline cursor-pointer">
                                                Publikasikan Sekarang &rarr;
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                            <!-- Berkas File Sertifikat -->
                            <td class="px-6 py-4">
                                @if ($cert->file)
                                    <a href="{{ route('admin.certificates.download', $cert) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-2xs">
                                        <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Unduh
                                    </a>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Tanpa File</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Lihat & Cetak Sertifikat Resmi -->
                                    <a href="{{ route('admin.certificates.certificate', $cert) }}" target="_blank" title="Lihat & Cetak Sertifikat Resmi Lanskap" class="rounded-lg p-1.5 text-amber-600 hover:bg-amber-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                                        </svg>
                                    </a>

                                    <!-- Print Transkrip Nilai -->
                                    <a href="{{ route('admin.certificates.print', $cert) }}" target="_blank" title="Cetak Lembar Transkrip Nilai" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-indigo-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                                        </svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('admin.certificates.edit', $cert) }}" title="Edit Nilai & Sertifikat" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-amber-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>

                                    <!-- Hapus -->
                                    <div x-data="{ openDelete: false }">
                                        <button @click="openDelete = true" title="Hapus" class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>

                                        <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="openDelete = false"></div>
                                            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-left border border-slate-100 z-10">
                                                <h3 class="text-base font-bold text-slate-900">Hapus Nilai & Sertifikat?</h3>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    Hapus arsip <strong>{{ $cert->name }}</strong> milik <strong>{{ $cert->student->user->name ?? 'Siswa' }}</strong>? Seluruh catatan nilai dan file sertifikat akan terhapus secara permanen.
                                                </p>
                                                <div class="mt-6 flex justify-end gap-2.5">
                                                    <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                                    <form action="{{ route('admin.certificates.destroy', $cert) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm">Hapus Permanen</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data nilai & sertifikat ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($certificates->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $certificates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
