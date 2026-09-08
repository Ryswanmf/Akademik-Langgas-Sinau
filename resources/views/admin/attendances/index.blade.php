@extends('layouts.admin', ['title' => 'Absensi Siswa', 'header' => 'Kelola Absensi Siswa'])

@section('content')
<div class="space-y-6" x-data="{ imageModalOpen: false, modalImg: '', modalTitle: '', modalSubtitle: '' }">
    
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Daftar Absensi & Presensi Siswa</h2>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider">Fitur Utama</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Rekapitulasi catatan waktu masuk, waktu pulang, koordinat lokasi, dan swafoto siswa LKP Langgas Sinau.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Trigger Auto-Alpa Otomatis -->
            <form action="{{ route('admin.attendances.auto-alpa') }}" method="POST" onsubmit="return confirm('Jalankan Auto-Alpa untuk hari ini? Seluruh siswa aktif yang belum memiliki catatan presensi akan otomatis dicatat sebagai Alpa.')">
                @csrf
                <button type="submit" class="rounded-xl border border-rose-200 bg-rose-50/70 hover:bg-rose-100 text-rose-700 px-3.5 py-2 text-xs font-bold shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer" title="Tandai siswa yang tidak hadir hari ini sebagai Alpa">
                    <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                    <span>Jalankan Auto-Alpa</span>
                </button>
            </form>

            <a href="{{ route('admin.attendances.rekap') }}" class="rounded-xl border border-indigo-200 bg-white px-3.5 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-50/70 shadow-2xs transition-all flex items-center gap-1.5">
                <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <span>Rekap & Cetak Absensi</span>
            </a>
            <a href="{{ route('admin.attendances.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 active:scale-95 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Input Presensi Manual</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Kehadiran (7 Kartu) -->
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Catatan</p>
            <p class="mt-1 text-2xl font-black text-slate-800 tracking-tight">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-400">Sesi tercatat</span>
        </div>
        <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Hadir Tepat</p>
            <p class="mt-1 text-2xl font-black text-emerald-700 tracking-tight">{{ $stats['hadir'] }}</p>
            <span class="text-[10px] text-emerald-600 font-medium">Tepat Waktu</span>
        </div>
        <div class="rounded-2xl border border-amber-200/80 bg-amber-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Terlambat</p>
            <p class="mt-1 text-2xl font-black text-amber-700 tracking-tight">{{ $stats['terlambat'] }}</p>
            <span class="text-[10px] text-amber-600 font-medium">> 09.30 WIB</span>
        </div>
        <div class="rounded-2xl border border-blue-200/80 bg-blue-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-blue-700 uppercase tracking-wider">Izin</p>
            <p class="mt-1 text-2xl font-black text-blue-700 tracking-tight">{{ $stats['izin'] }}</p>
            <span class="text-[10px] text-blue-600 font-medium">Disetujui</span>
        </div>
        <div class="rounded-2xl border border-orange-200/80 bg-orange-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-orange-700 uppercase tracking-wider">Sakit</p>
            <p class="mt-1 text-2xl font-black text-orange-700 tracking-tight">{{ $stats['sakit'] }}</p>
            <span class="text-[10px] text-orange-600 font-medium">Surat/Izin</span>
        </div>
        <div class="rounded-2xl border border-rose-200/80 bg-rose-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-rose-700 uppercase tracking-wider">Alpa</p>
            <p class="mt-1 text-2xl font-black text-rose-700 tracking-tight">{{ $stats['alpa'] }}</p>
            <span class="text-[10px] text-rose-600 font-medium">Tanpa Keterangan</span>
        </div>
        <div class="rounded-2xl border border-indigo-200/80 bg-indigo-50/60 p-4 shadow-2xs">
            <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">% Kehadiran</p>
            <p class="mt-1 text-2xl font-black text-indigo-700 tracking-tight">{{ $stats['rate'] }}%</p>
            <span class="text-[10px] text-indigo-600 font-medium">Tingkat Disiplin</span>
        </div>
    </div>

    <!-- Filter Bar Presensi (Pencarian, Program, Tanggal, Status) -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <!-- 1. Pencarian Siswa -->
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Cari Siswa</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Nama, NIS, atau Sekolah..." 
                        class="w-full rounded-xl border border-slate-200 pl-8 pr-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    >
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
            </div>

            <!-- 2. Filter Program Keahlian -->
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Program Keahlian</label>
                <select name="program" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Program</option>
                    @foreach ($programs as $prog)
                        <option value="{{ $prog }}" {{ request('program') == $prog ? 'selected' : '' }}>
                            {{ $prog }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Filter Tanggal -->
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Absensi</label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}" 
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
            </div>

            <!-- 4. Filter Status -->
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status Kehadiran</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>

            <!-- 5. Tombol Filter & Reset -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-xl bg-slate-800 py-2 px-3 text-xs font-bold text-white hover:bg-slate-900 transition-colors cursor-pointer">
                    Terapkan
                </button>
                @if (request()->hasAny(['search', 'program', 'date', 'status', 'student_id', 'class_id']))
                    <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Catatan Absensi Siswa -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-2xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/40">
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Catatan Riwayat Presensi</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Memuat waktu masuk, waktu pulang, koordinat GPS, dan bukti swafoto.</p>
            </div>
            <div class="text-xs text-slate-500">
                Total hasil: <strong class="text-slate-800">{{ $attendances->total() }}</strong> rekaman
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/90 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Siswa</th>
                        <th class="px-5 py-3.5">Presensi Masuk</th>
                        <th class="px-5 py-3.5">Presensi Pulang</th>
                        <th class="px-5 py-3.5">Jarak LKP</th>
                        <th class="px-5 py-3.5 text-center">Swafoto</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Keterangan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $att)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Tanggal -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-bold text-slate-800">{{ $att->date->translatedFormat('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $att->date->translatedFormat('l') }}</p>
                            </td>

                            <!-- Siswa -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3 min-w-[200px]">
                                    <img src="{{ $att->student->photo_url }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-slate-200 shrink-0" alt="">
                                    <div>
                                        <p class="font-bold text-slate-900 leading-snug">{{ $att->student->user->name ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-500 font-mono">{{ $att->student->student_number }}</p>
                                        @if ($att->student->program || $att->student->school_origin)
                                            <p class="text-[10px] text-indigo-600 font-semibold truncate max-w-[180px]">
                                                {{ $att->student->program ?: $att->student->school_origin }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Presensi Masuk -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($att->check_in_time)
                                    <div class="flex items-center gap-1.5">
                                        <span class="flex h-2 w-2 rounded-full {{ $att->status === 'terlambat' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                        <span class="font-mono font-bold text-slate-800">{{ $att->formatted_check_in_time }}</span>
                                    </div>
                                    <span class="text-[10px] {{ $att->status === 'terlambat' ? 'text-amber-600 font-medium' : 'text-slate-400' }}">
                                        {{ $att->status === 'terlambat' ? 'Terlambat Masuk' : 'Tepat Waktu' }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-mono">--:-- WIB</span>
                                    <span class="block text-[10px] text-slate-400">Belum masuk</span>
                                @endif
                            </td>

                            <!-- Presensi Pulang -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($att->check_out_time)
                                    <div class="flex items-center gap-1.5">
                                        <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span class="font-mono font-bold text-slate-800">{{ $att->formatted_check_out_time }}</span>
                                    </div>
                                    <span class="text-[10px] text-blue-600 font-medium">Selesai Sesi</span>
                                @else
                                    <span class="text-slate-400 font-mono">--:-- WIB</span>
                                    <span class="block text-[10px] text-slate-400">Belum pulang</span>
                                @endif
                            </td>

                            <!-- Jarak LKP -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($att->check_in_distance !== null)
                                    <div class="flex items-center gap-1.5 font-bold {{ $att->is_check_in_within_radius ? 'text-emerald-700' : 'text-amber-700' }}">
                                        <span>{{ $att->check_in_distance_formatted }}</span>
                                        @if ($att->is_check_in_within_radius)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded-md text-[9px] bg-emerald-100 text-emerald-800 font-extrabold" title="Dalam Toleransi Radius LKP">
                                                ≤ 150m
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded-md text-[9px] bg-amber-100 text-amber-800 font-extrabold" title="Di Luar Toleransi Radius LKP">
                                                Luar
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 font-medium">-</span>
                                @endif
                            </td>

                            <!-- Swafoto (Masuk & Pulang) -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($att->check_in_photo_url)
                                        <button 
                                            @click="modalImg = '{{ $att->check_in_photo_url }}'; modalTitle = 'Swafoto Presensi Masuk'; modalSubtitle = '{{ $att->student->user->name ?? 'Siswa' }} • {{ $att->date->translatedFormat('d M Y') }} ({{ $att->formatted_check_in_time }})'; imageModalOpen = true;" 
                                            class="relative group h-8 w-8 rounded-xl overflow-hidden ring-2 ring-emerald-500/80 shadow-2xs hover:scale-105 transition-all cursor-pointer"
                                            title="Klik untuk zoom swafoto masuk"
                                        >
                                            <img src="{{ $att->check_in_photo_url }}" class="h-full w-full object-cover" alt="Foto Masuk">
                                            <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[9px] font-bold">
                                                IN
                                            </span>
                                        </button>
                                    @endif

                                    @if ($att->check_out_photo_url)
                                        <button 
                                            @click="modalImg = '{{ $att->check_out_photo_url }}'; modalTitle = 'Swafoto Presensi Pulang'; modalSubtitle = '{{ $att->student->user->name ?? 'Siswa' }} • {{ $att->date->translatedFormat('d M Y') }} ({{ $att->formatted_check_out_time }})'; imageModalOpen = true;" 
                                            class="relative group h-8 w-8 rounded-xl overflow-hidden ring-2 ring-blue-500/80 shadow-2xs hover:scale-105 transition-all cursor-pointer"
                                            title="Klik untuk zoom swafoto pulang"
                                        >
                                            <img src="{{ $att->check_out_photo_url }}" class="h-full w-full object-cover" alt="Foto Pulang">
                                            <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[9px] font-bold">
                                                OUT
                                            </span>
                                        </button>
                                    @endif

                                    @if (!$att->check_in_photo_url && !$att->check_out_photo_url)
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($att->status === 'hadir')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-extrabold text-emerald-700 border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Hadir
                                    </span>
                                @elseif ($att->status === 'terlambat')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-extrabold text-amber-700 border border-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Terlambat
                                    </span>
                                @elseif ($att->status === 'izin')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-extrabold text-blue-700 border border-blue-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                        Izin
                                    </span>
                                @elseif ($att->status === 'sakit')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-extrabold text-orange-700 border border-orange-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                        Sakit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-extrabold text-rose-700 border border-rose-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Alpa
                                    </span>
                                @endif
                            </td>

                            <!-- Keterangan -->
                            <td class="px-5 py-4 text-slate-500 max-w-[200px] truncate" title="{{ $att->note }}">
                                {{ $att->note ?: '-' }}
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.attendances.print-student', $att->student_id) }}" title="Cetak Rekap Absensi Siswa" class="rounded-xl p-2 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.attendances.edit', $att) }}" title="Edit Data Absensi" class="rounded-xl p-2 text-slate-400 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <div x-data="{ openDelete: false }">
                                        <button @click="openDelete = true" title="Hapus Data Absensi" class="rounded-xl p-2 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors cursor-pointer">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>

                                        <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="openDelete = false"></div>
                                            <div class="relative w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl text-left border border-slate-100 z-10">
                                                <h3 class="text-base font-extrabold text-slate-900">Hapus Catatan Absensi?</h3>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    Anda yakin ingin menghapus data absensi siswa <strong>{{ $att->student->user->name }}</strong> pada tanggal {{ $att->date->format('d/m/Y') }}?
                                                </p>
                                                <div class="mt-6 flex justify-end gap-2.5">
                                                    <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">Batal</button>
                                                    <form action="{{ route('admin.attendances.destroy', $att) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm transition-colors cursor-pointer">Hapus</button>
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
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    <p class="font-bold text-slate-600">Tidak ada catatan absensi yang ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Coba sesuaikan filter pencarian atau tanggal yang Anda pilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($attendances->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/40">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Zoom Preview Swafoto -->
    <div 
        x-show="imageModalOpen" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs"
        @click="imageModalOpen = false"
        @keydown.escape.window="imageModalOpen = false"
    >
        <div class="relative max-w-md w-full bg-white rounded-3xl p-5 shadow-2xl border border-slate-100" @click.stop>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h4 class="text-sm font-extrabold text-slate-900" x-text="modalTitle"></h4>
                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="modalSubtitle"></p>
                </div>
                <button @click="imageModalOpen = false" class="rounded-xl p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-4 aspect-4/3 rounded-2xl overflow-hidden bg-slate-950 flex items-center justify-center border border-slate-200">
                <img :src="modalImg" class="h-full w-full object-cover" alt="Swafoto Zoom">
            </div>
            <div class="mt-4 flex justify-end">
                <button @click="imageModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 cursor-pointer transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
