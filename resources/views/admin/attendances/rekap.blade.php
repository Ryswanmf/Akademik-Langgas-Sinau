@extends('layouts.admin', ['title' => 'Rekapitulasi Absensi', 'header' => 'Rekapitulasi Absensi Siswa'])

@section('content')
<div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Rekapitulasi Kehadiran Siswa</h2>
                <span class="rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider">Laporan</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Akumulasi status presensi, tingkat kehadiran (persentase), dan cetak lembar hasil absensi per siswa.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-2xs transition-colors flex items-center gap-1.5">
                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Daftar Absensi</span>
            </a>
            <a href="{{ route('admin.attendances.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 active:scale-95 transition-all flex items-center gap-1.5">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Input Presensi</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar (Pencarian & Program Keahlian) -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.attendances.rekap') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2.5 flex-1">
                <!-- Pencarian Nama / NIS / Asal Sekolah -->
                <div class="relative w-full sm:w-72">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama, NIS, atau asal sekolah..." 
                        class="w-full rounded-xl border border-slate-200 pl-9 pr-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <!-- Filter Program Keahlian -->
                @if (isset($programs) && $programs->count() > 0)
                    <select name="program" onchange="this.form.submit()" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Semua Program Keahlian --</option>
                        @foreach ($programs as $prog)
                            <option value="{{ $prog }}" {{ request('program') == $prog ? 'selected' : '' }}>
                                {{ $prog }}
                            </option>
                        @endforeach
                    </select>
                @endif

                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition-colors cursor-pointer">
                    Cari & Filter
                </button>

                @if (request()->hasAny(['search', 'program', 'class_id']))
                    <a href="{{ route('admin.attendances.rekap') }}" class="px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                        Reset Filter
                    </a>
                @endif
            </div>

            <span class="text-xs text-slate-500 font-medium whitespace-nowrap">
                Total: <strong class="text-slate-800">{{ count($students) }}</strong> siswa terdaftar
            </span>
        </form>
    </div>

    <!-- Tabel Rekapitulasi Kehadiran Siswa -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/90 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-4">Siswa</th>
                        <th class="px-5 py-4 text-center">Total Sesi</th>
                        <th class="px-5 py-4 text-center">Hadir Tepat</th>
                        <th class="px-5 py-4 text-center">Terlambat</th>
                        <th class="px-5 py-4 text-center">Izin</th>
                        <th class="px-5 py-4 text-center">Sakit</th>
                        <th class="px-5 py-4 text-center">Alpa</th>
                        <th class="px-5 py-4 text-center">Persentase Kehadiran</th>
                        <th class="px-5 py-4 text-right">Aksi Cetak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $row)
                        @php
                            $std = $row['student'];
                            $stats = $row['stats'];
                            $rate = $stats['rate'];
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Profil Siswa -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-[220px]">
                                    <img src="{{ $std->photo_url }}" class="h-10 w-10 rounded-2xl object-cover ring-1 ring-slate-200 shrink-0 shadow-2xs" alt="">
                                    <div>
                                        <p class="font-extrabold text-slate-900 leading-snug">{{ $std->user->name }}</p>
                                        <p class="text-[11px] text-slate-500 font-mono">{{ $std->student_number }}</p>
                                        @if ($std->program || $std->school_origin)
                                            <p class="text-[10px] text-indigo-600 font-semibold truncate max-w-[200px]">
                                                {{ $std->program ?: $std->school_origin }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Total Sesi -->
                            <td class="px-5 py-4 text-center font-extrabold text-slate-800 text-sm">
                                {{ $stats['total'] }}
                            </td>

                            <!-- Hadir Tepat -->
                            <td class="px-5 py-4 text-center font-extrabold text-emerald-600 text-sm">
                                {{ $stats['hadir'] }}
                            </td>

                            <!-- Terlambat -->
                            <td class="px-5 py-4 text-center font-extrabold text-amber-600 text-sm">
                                {{ $stats['terlambat'] }}
                            </td>

                            <!-- Izin -->
                            <td class="px-5 py-4 text-center font-extrabold text-blue-600 text-sm">
                                {{ $stats['izin'] }}
                            </td>

                            <!-- Sakit -->
                            <td class="px-5 py-4 text-center font-extrabold text-orange-600 text-sm">
                                {{ $stats['sakit'] }}
                            </td>

                            <!-- Alpa -->
                            <td class="px-5 py-4 text-center font-extrabold text-rose-600 text-sm">
                                {{ $stats['alpa'] }}
                            </td>

                            <!-- Persentase Kehadiran Bar -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-2.5">
                                    <div class="w-24 bg-slate-100 h-2.5 rounded-full overflow-hidden border border-slate-200">
                                        <div 
                                            class="h-full rounded-full transition-all duration-500 {{ $rate >= 80 ? 'bg-emerald-500' : ($rate >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                            style="width: {{ $rate }}%"
                                        ></div>
                                    </div>
                                    <span class="font-mono font-black text-xs {{ $rate >= 80 ? 'text-emerald-700' : ($rate >= 60 ? 'text-amber-700' : 'text-rose-700') }}">
                                        {{ $rate }}%
                                    </span>
                                </div>
                            </td>

                            <!-- Tombol Cetak -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a 
                                    href="{{ route('admin.attendances.print-student', $std->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold text-xs transition-colors shadow-2xs"
                                    title="Pratinjau & Cetak Lembar Rekap Siswa"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                                    </svg>
                                    <span>Cetak Lembar</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <p class="font-bold text-slate-600">Tidak ada data siswa yang ditemukan.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Pastikan kata kunci pencarian atau filter program sudah tepat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
