@extends('layouts.admin', ['title' => 'Detail Siswa - ' . $student->user->name, 'header' => 'Detail Informasi Siswa'])

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 flex items-center gap-1.5">
            &larr; Kembali ke Daftar Siswa
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.attendances.print-student', $student) }}" target="_blank" class="rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 flex items-center gap-1.5 shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                </svg>
                Cetak Hasil Absensi
            </a>
            <a href="{{ route('admin.students.edit', $student) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-700 transition-colors">
                Edit Profil Siswa
            </a>
        </div>
    </div>

    <!-- Student Info Profile Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <img src="{{ $student->photo_url }}" class="h-24 w-24 rounded-2xl object-cover ring-4 ring-slate-100 shadow-sm shrink-0" alt="">
            <div class="space-y-1.5 flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-800">{{ $student->user->name }}</h2>
                    <span class="inline-flex rounded-full px-3 py-0.5 text-xs font-bold capitalize
                        {{ $student->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : '' }}
                        {{ $student->status === 'nonaktif' ? 'bg-rose-50 text-rose-700' : '' }}
                        {{ $student->status === 'lulus' ? 'bg-blue-50 text-blue-700' : '' }}">
                        {{ $student->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-mono">NIS: <span class="font-bold text-slate-700">{{ $student->student_number }}</span> • Kelas: <span class="font-bold text-slate-700">{{ $student->class->name ?? 'Belum ada' }}</span></p>
                <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-slate-600 pt-1">
                    <span><strong>Email:</strong> {{ $student->user->email }}</span>
                    <span><strong>No. HP:</strong> {{ $student->phone ?? '-' }}</span>
                    <span><strong>Program:</strong> {{ $student->program }}</span>
                    <span><strong>Asal Sekolah/Instansi:</strong> <span class="font-bold text-emerald-700">{{ $student->school_origin ?: '-' }}</span></span>
                    <span><strong>Masuk:</strong> {{ $student->entry_date ? $student->entry_date->translatedFormat('d M Y') : '-' }}</span>
                </div>
                <p class="text-xs text-slate-500 pt-1"><strong>Alamat:</strong> {{ $student->address ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Attendance Stat Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Tingkat Kehadiran</p>
            <p class="mt-1 text-2xl font-black text-emerald-600">{{ $stats['rate'] }}%</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Total Sesi</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Hadir</p>
            <p class="mt-1 text-2xl font-black text-emerald-600">{{ $stats['hadir'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Izin</p>
            <p class="mt-1 text-2xl font-black text-blue-600">{{ $stats['izin'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Sakit</p>
            <p class="mt-1 text-2xl font-black text-amber-600">{{ $stats['sakit'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 uppercase">Alpa</p>
            <p class="mt-1 text-2xl font-black text-rose-600">{{ $stats['alpa'] }}</p>
        </div>
    </div>

    <!-- Tabs with Alpine.js -->
    <div x-data="{ tab: 'absensi' }" class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="flex border-b border-slate-100 bg-slate-50/50 px-6 pt-3 gap-2 overflow-x-auto">
            <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'border-indigo-600 text-indigo-600 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 px-4 py-3 text-xs transition-colors rounded-t-xl">
                Riwayat Absensi ({{ $student->attendances->count() }})
            </button>
            <button @click="tab = 'nilai'" :class="tab === 'nilai' ? 'border-indigo-600 text-indigo-600 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 px-4 py-3 text-xs transition-colors rounded-t-xl">
                Daftar Nilai ({{ $student->grades->count() }})
            </button>
            <button @click="tab = 'sertifikat'" :class="tab === 'sertifikat' ? 'border-indigo-600 text-indigo-600 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 px-4 py-3 text-xs transition-colors rounded-t-xl">
                Nilai & Sertifikat ({{ $student->certificates->count() }})
            </button>
            <button @click="tab = 'izin'" :class="tab === 'izin' ? 'border-indigo-600 text-indigo-600 bg-white font-bold' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 px-4 py-3 text-xs transition-colors rounded-t-xl">
                Pengajuan Izin ({{ $student->permissions->count() }})
            </button>
        </div>

        <div class="p-6">
            <!-- Tab 1: Absensi -->
            <div x-show="tab === 'absensi'">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3">Jadwal / Materi</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($student->attendances as $att)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 font-medium text-slate-700">{{ $att->date->translatedFormat('d M Y') }}</td>
                                    <td class="py-3 font-semibold text-slate-800">{{ $att->schedule->title ?? 'Sesi Pembelajaran' }}</td>
                                    <td class="py-3">
                                        @if ($att->status === 'hadir')
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 font-bold text-emerald-700 text-[10px]">Hadir</span>
                                        @elseif ($att->status === 'izin')
                                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-0.5 font-bold text-blue-700 text-[10px]">Izin</span>
                                        @elseif ($att->status === 'sakit')
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-0.5 font-bold text-amber-700 text-[10px]">Sakit</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-0.5 font-bold text-rose-700 text-[10px]">Alpa</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-slate-500">{{ $att->note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">Belum ada riwayat absensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 2: Nilai -->
            <div x-show="tab === 'nilai'" x-cloak>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-slate-400 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="pb-3">Materi / Subjek</th>
                                <th class="pb-3">Kelas</th>
                                <th class="pb-3">Nilai</th>
                                <th class="pb-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($student->grades as $grade)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-3 font-bold text-slate-800">{{ $grade->subject }}</td>
                                    <td class="py-3 text-slate-600">{{ $grade->class->name ?? '-' }}</td>
                                    <td class="py-3 font-black text-indigo-600 text-sm">{{ $grade->score }}</td>
                                    <td class="py-3 text-slate-500">{{ $grade->description ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400">Belum ada nilai yang diinput.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Nilai & Sertifikat -->
            <div x-show="tab === 'sertifikat'" x-cloak>
                <div class="space-y-4">
                    @forelse ($student->certificates as $cert)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-100 text-purple-600 font-bold shrink-0">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h4 class="text-xs font-bold text-slate-800">{{ $cert->name }}</h4>
                                            @if ($cert->is_published)
                                                <span class="rounded-md bg-emerald-100 text-emerald-800 px-1.5 py-0.5 text-[9px] font-bold">Dipublikasikan</span>
                                            @else
                                                <span class="rounded-md bg-amber-100 text-amber-800 px-1.5 py-0.5 text-[9px] font-bold">Draft</span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-mono">{{ $cert->certificate_number }} • Terbit: {{ $cert->issued_date ? $cert->issued_date->translatedFormat('d M Y') : '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    @if ($cert->final_score !== null)
                                        <div class="text-right mr-2">
                                            <span class="text-sm font-black text-slate-800">{{ number_format($cert->final_score, 1) }}</span>
                                            <span class="rounded bg-purple-50 px-1.5 py-0.5 text-[10px] font-bold text-purple-700 block">{{ $cert->grade_predicate ?: 'Lulus' }}</span>
                                        </div>
                                    @endif
                                    <a href="{{ route('admin.certificates.print', $cert) }}" target="_blank" class="rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        Cetak
                                    </a>
                                    @if ($cert->file)
                                        <a href="{{ route('admin.certificates.download', $cert) }}" class="rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-50">
                                            Unduh
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if ($cert->final_score !== null)
                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 pt-2 border-t border-slate-200/60 text-[10px]">
                                    <div class="bg-white p-1.5 rounded border border-slate-200/60 text-center">
                                        <span class="text-slate-400 block text-[9px]">Disiplin</span>
                                        <span class="font-bold text-slate-700">{{ $cert->score_discipline ?? '-' }}</span>
                                    </div>
                                    <div class="bg-white p-1.5 rounded border border-slate-200/60 text-center">
                                        <span class="text-slate-400 block text-[9px]">Inisiatif</span>
                                        <span class="font-bold text-slate-700">{{ $cert->score_initiative ?? '-' }}</span>
                                    </div>
                                    <div class="bg-white p-1.5 rounded border border-slate-200/60 text-center">
                                        <span class="text-slate-400 block text-[9px]">Kerja sama</span>
                                        <span class="font-bold text-slate-700">{{ $cert->score_teamwork ?? '-' }}</span>
                                    </div>
                                    <div class="bg-white p-1.5 rounded border border-slate-200/60 text-center">
                                        <span class="text-slate-400 block text-[9px]">T. Jawab</span>
                                        <span class="font-bold text-slate-700">{{ $cert->score_responsibility ?? '-' }}</span>
                                    </div>
                                    <div class="bg-white p-1.5 rounded border border-slate-200/60 text-center">
                                        <span class="text-slate-400 block text-[9px]">Sikap</span>
                                        <span class="font-bold text-slate-700">{{ $cert->score_attitude ?? '-' }}</span>
                                    </div>
                                    <div class="bg-white p-1.5 rounded border border-slate-200/60 text-center">
                                        <span class="text-slate-400 block text-[9px]">Kehadiran</span>
                                        <span class="font-bold text-slate-700">{{ $cert->score_attendance ?? '-' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada nilai & sertifikat untuk siswa ini.</p>
                    @endforelse
                </div>
            </div>

            <!-- Tab 4: Izin -->
            <div x-show="tab === 'izin'" x-cloak>
                <div class="space-y-3">
                    @forelse ($student->permissions as $perm)
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">
                                    {{ $perm->type }} • {{ $perm->date->translatedFormat('d M Y') }}
                                </span>
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold capitalize
                                    {{ $perm->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $perm->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ $perm->status === 'ditolak' ? 'bg-rose-50 text-rose-700' : '' }}">
                                    {{ $perm->status }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-700"><strong>Alasan:</strong> {{ $perm->reason }}</p>
                            @if ($perm->admin_note)
                                <p class="text-xs text-slate-500 mt-1 italic"><strong>Catatan Admin:</strong> {{ $perm->admin_note }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="py-6 text-center text-xs text-slate-400">Belum ada riwayat perizinan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
