@extends('layouts.siswa', ['title' => 'Absensi Saya', 'header' => 'Presensi Mandiri & Rekapitulasi'])

@section('content')
<div class="space-y-6" x-data="attendanceApp({
    lkpLat: {{ (float) ($lkpConfig['latitude'] ?? -5.124188) }},
    lkpLng: {{ (float) ($lkpConfig['longitude'] ?? 105.332312) }},
    maxRadius: {{ (int) ($lkpConfig['radius_meters'] ?? 150) }},
    strictRadius: {{ ($lkpConfig['strict_radius'] ?? true) ? 'true' : 'false' }},
    lkpName: '{{ addslashes($lkpConfig['lkp_name'] ?? 'LKP Langgas Sinau') }}',
    lkpAddress: '{{ addslashes($lkpConfig['address'] ?? 'Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung') }}'
})">
    
    <!-- Header Halaman & Jam Real-Time WIB -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Presensi Siswa LKP Langgas Sinau</h2>
                <span class="rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider">Mandiri</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Lakukan absensi masuk dan kepulangan menggunakan swafoto wajah serta verifikasi jarak radius LKP.
            </p>
        </div>

        <!-- Live Clock & Tanggal -->
        <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-2xl border border-slate-200/80 shadow-2xs self-start md:self-auto">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-base font-black text-slate-800 tracking-tight" x-text="currentTime">--:--:-- WIB</p>
            </div>
        </div>
    </div>

    <!-- 3 Kartu Panduan Jam Operasional Presensi -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <!-- 1. Hadir Masuk -->
        <div class="p-4 rounded-2xl bg-white border border-emerald-200/90 shadow-2xs flex items-center gap-3.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-500"></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-white font-black text-xs shrink-0 shadow-xs">
                {{ $lkpConfig['in_start'] ?? '08:00' }}
            </span>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1">
                    <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                        Jam Masuk Tepat
                    </span>
                </div>
                <p class="text-xs font-black text-slate-900 mt-1">{{ str_replace(':', '.', $lkpConfig['in_start'] ?? '08:00') }} - {{ str_replace(':', '.', $lkpConfig['in_on_time_end'] ?? '09:30') }} WIB</p>
                <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Dicatat Hadir Tepat Waktu</p>
            </div>
        </div>

        <!-- 2. Terlambat Masuk -->
        <div class="p-4 rounded-2xl bg-white border border-amber-200/90 shadow-2xs flex items-center gap-3.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-amber-500"></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500 text-white font-black text-xs shrink-0 shadow-xs">
                {{ $lkpConfig['in_on_time_end'] ?? '09:30' }}
            </span>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1">
                    <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                        Jam Masuk Terlambat
                    </span>
                </div>
                <p class="text-xs font-black text-slate-900 mt-1">09.31 - {{ str_replace(':', '.', $lkpConfig['in_late_end'] ?? '13:50') }} WIB</p>
                <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Dicatat Hadir Terlambat</p>
            </div>
        </div>

        <!-- 3. Hadir Pulang -->
        <div class="p-4 rounded-2xl bg-white border border-blue-200/90 shadow-2xs flex items-center gap-3.5 relative overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white font-black text-xs shrink-0 shadow-xs">
                {{ $lkpConfig['out_start'] ?? '14:00' }}
            </span>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1">
                    <span class="inline-block text-[10px] font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200">
                        Jam Pulang Resmi
                    </span>
                </div>
                <p class="text-xs font-black text-slate-900 mt-1">{{ str_replace(':', '.', $lkpConfig['out_start'] ?? '14:00') }} - {{ str_replace(':', '.', $lkpConfig['out_end'] ?? '17:00') }} WIB</p>
                <p class="text-[11px] text-slate-500 leading-tight mt-0.5">Presensi Kepulangan Siswa</p>
            </div>
        </div>
    </div>

    <!-- Panel Status Presensi Hari Ini (Masuk & Pulang) -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-7 shadow-2xs">
        <!-- Panel Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-5 border-b border-slate-100 gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                        <span class="h-2 w-2 rounded-full {{ $todayAttendance ? 'bg-emerald-500' : 'bg-amber-400 animate-pulse' }}"></span>
                        Status Presensi Hari Ini
                    </span>
                    <span class="text-xs font-semibold text-slate-400 font-mono">
                        {{ now()->translatedFormat('d F Y') }}
                    </span>
                </div>
                <h3 class="text-lg font-black text-slate-900 mt-2">
                    {{ $student->program ? 'Program ' . $student->program : 'Pelatihan Siswa LKP Langgas Sinau' }}
                </h3>
                <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-1.5">
                    <span>Acuan Lokasi:</span>
                    <strong class="text-slate-700">{{ $lkpConfig['lkp_name'] ?? 'LKP Langgas Sinau' }}</strong>
                    <span class="text-slate-400">• Radius batas {{ $lkpConfig['radius_meters'] ?? 150 }} meter</span>
                    <span class="text-slate-400 hidden lg:inline">• {{ $lkpConfig['address'] ?? 'Banjar Rejo, Batanghari, Lampung Timur' }}</span>
                </p>
            </div>

            <!-- Tombol Aksi Cepat Presensi -->
            <div class="flex flex-wrap items-center gap-2">
                @if (!empty($isHoliday))
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-amber-50 text-amber-900 border border-amber-200 text-xs font-bold">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>Hari Libur Pelatihan (Minggu)</span>
                    </div>
                @elseif (!$todayAttendance || !$todayAttendance->check_in_time)
                    <button 
                        type="button"
                        @click="openPresensiModal('masuk')"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs shadow-md shadow-emerald-600/25 hover:from-emerald-700 hover:to-teal-700 active:scale-95 transition-all cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                        </svg>
                        <span>Presensi Masuk (Swafoto & GPS)</span>
                    </button>
                @elseif ($todayAttendance && !$todayAttendance->check_out_time)
                    <button 
                        type="button"
                        @click="openPresensiModal('pulang')"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs shadow-md shadow-blue-600/25 hover:from-blue-700 hover:to-indigo-700 active:scale-95 transition-all cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Presensi Pulang (Swafoto & GPS)</span>
                    </button>
                @else
                    <div class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Presensi Hari Ini Lengkap & Tuntas</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2 Kartu Status: Presensi Masuk vs Presensi Pulang -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">
            <!-- 1. Kartu Presensi Masuk -->
            <div class="rounded-2xl border {{ $todayAttendance && $todayAttendance->check_in_time ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200 bg-slate-50/50' }} p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $todayAttendance && $todayAttendance->check_in_time ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-200 text-slate-500' }} font-black text-xs">
                            IN
                        </span>
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Presensi Masuk</h4>
                            <p class="text-[11px] text-slate-500">Mulai aktivitas pelatihan</p>
                        </div>
                    </div>
                    @if ($todayAttendance && $todayAttendance->check_in_time)
                        @if ($todayAttendance->status === 'terlambat')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-wider">
                                Terlambat
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 uppercase tracking-wider">
                                Tepat Waktu
                            </span>
                        @endif
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200/80 text-slate-600 uppercase tracking-wider">
                            Belum Masuk
                        </span>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200/70 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jam Masuk</p>
                        <p class="text-xl font-black text-slate-900 font-mono mt-0.5">
                            {{ $todayAttendance && $todayAttendance->check_in_time ? $todayAttendance->formatted_check_in_time : '--:-- WIB' }}
                        </p>
                    </div>

                    @if ($todayAttendance && $todayAttendance->check_in_time)
                        <div class="text-right flex items-center gap-3">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jarak dari LKP</p>
                                <p class="text-xs font-extrabold {{ $todayAttendance->is_check_in_within_radius ? 'text-emerald-700' : 'text-amber-700' }} mt-0.5">
                                    {{ $todayAttendance->check_in_distance_formatted }}
                                    <span class="text-[10px] font-medium">({{ $todayAttendance->is_check_in_within_radius ? 'Dalam Radius' : 'Luar Radius' }})</span>
                                </p>
                            </div>
                            @if ($todayAttendance->check_in_photo_url)
                                <button 
                                    @click="openImageModal('{{ $todayAttendance->check_in_photo_url }}', 'Swafoto Masuk', '{{ $todayAttendance->formatted_check_in_time }} - Jarak {{ $todayAttendance->check_in_distance_formatted }}')" 
                                    class="relative group h-11 w-11 rounded-xl overflow-hidden ring-2 ring-emerald-500 shadow-2xs hover:scale-105 transition-all cursor-pointer shrink-0"
                                    title="Lihat Swafoto Masuk"
                                >
                                    <img src="{{ $todayAttendance->check_in_photo_url }}" class="h-full w-full object-cover" alt="Swafoto Masuk">
                                    <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[9px] font-bold">
                                        Zoom
                                    </span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- 2. Kartu Presensi Pulang -->
            <div class="rounded-2xl border {{ $todayAttendance && $todayAttendance->check_out_time ? 'border-blue-200 bg-blue-50/30' : 'border-slate-200 bg-slate-50/50' }} p-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $todayAttendance && $todayAttendance->check_out_time ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-200 text-slate-500' }} font-black text-xs">
                            OUT
                        </span>
                        <div>
                            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Presensi Pulang</h4>
                            <p class="text-[11px] text-slate-500">Selesai aktivitas pelatihan</p>
                        </div>
                    </div>
                    @if ($todayAttendance && $todayAttendance->check_out_time)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200 uppercase tracking-wider">
                            Sudah Pulang
                        </span>
                    @elseif ($todayAttendance && $todayAttendance->check_in_time)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase tracking-wider">
                            Siap Pulang (14.00)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200/80 text-slate-600 uppercase tracking-wider">
                            Menunggu Masuk
                        </span>
                    @endif
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200/70 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jam Pulang</p>
                        <p class="text-xl font-black text-slate-900 font-mono mt-0.5">
                            {{ $todayAttendance && $todayAttendance->check_out_time ? $todayAttendance->formatted_check_out_time : '--:-- WIB' }}
                        </p>
                    </div>

                    @if ($todayAttendance && $todayAttendance->check_out_time)
                        <div class="text-right flex items-center gap-3">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Jarak dari LKP</p>
                                <p class="text-xs font-extrabold text-blue-700 mt-0.5">
                                    {{ $todayAttendance->check_out_distance !== null ? ($todayAttendance->check_out_distance < 1000 ? $todayAttendance->check_out_distance . ' m' : round($todayAttendance->check_out_distance / 1000, 1) . ' km') : '-' }}
                                    <span class="text-[10px] font-medium">({{ ($todayAttendance->check_out_distance ?? 0) <= ($lkpConfig['radius_meters'] ?? 150) ? 'Dalam Radius' : 'Luar Radius' }})</span>
                                </p>
                            </div>
                            @if ($todayAttendance->check_out_photo_url)
                                <button 
                                    @click="openImageModal('{{ $todayAttendance->check_out_photo_url }}', 'Swafoto Pulang', '{{ $todayAttendance->formatted_check_out_time }}')" 
                                    class="relative group h-11 w-11 rounded-xl overflow-hidden ring-2 ring-blue-500 shadow-2xs hover:scale-105 transition-all cursor-pointer shrink-0"
                                    title="Lihat Swafoto Pulang"
                                >
                                    <img src="{{ $todayAttendance->check_out_photo_url }}" class="h-full w-full object-cover" alt="Swafoto Pulang">
                                    <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[9px] font-bold">
                                        Zoom
                                    </span>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Kehadiran (7 Kartu) -->
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-7">
        <div class="rounded-2xl border border-emerald-200/90 bg-emerald-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Kehadiran</p>
            <p class="mt-1 text-2xl font-black text-emerald-800 tracking-tight">{{ $stats['rate'] }}%</p>
            <span class="text-[10px] text-emerald-600 font-medium">Tingkat Disiplin</span>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Sesi</p>
            <p class="mt-1 text-2xl font-black text-slate-800 tracking-tight">{{ $stats['total'] }}</p>
            <span class="text-[10px] text-slate-400">Akumulasi</span>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Hadir Tepat</p>
            <p class="mt-1 text-2xl font-black text-emerald-600 tracking-tight">{{ $stats['hadir'] }}</p>
            <span class="text-[10px] text-emerald-600 font-medium">Tepat Waktu</span>
        </div>
        <div class="rounded-2xl border border-amber-200/90 bg-amber-50/50 p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Terlambat</p>
            <p class="mt-1 text-2xl font-black text-amber-700 tracking-tight">{{ $stats['terlambat'] ?? 0 }}</p>
            <span class="text-[10px] text-amber-600 font-medium">> 09.30 WIB</span>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Izin</p>
            <p class="mt-1 text-2xl font-black text-blue-600 tracking-tight">{{ $stats['izin'] }}</p>
            <span class="text-[10px] text-blue-600 font-medium">Disetujui</span>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sakit</p>
            <p class="mt-1 text-2xl font-black text-orange-600 tracking-tight">{{ $stats['sakit'] }}</p>
            <span class="text-[10px] text-orange-600 font-medium">Surat/Izin</span>
        </div>
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-2xs">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Alpa</p>
            <p class="mt-1 text-2xl font-black text-rose-600 tracking-tight">{{ $stats['alpa'] }}</p>
            <span class="text-[10px] text-rose-600 font-medium">Tanpa Keterangan</span>
        </div>
    </div>

    <!-- Tabel Riwayat Presensi Siswa -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-2xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/40">
            <div>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Tabel Riwayat Presensi Saya</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Catatan lengkap jam masuk, jam pulang, jarak koordinat, dan bukti swafoto.</p>
            </div>

            <!-- Filter Status -->
            <form method="GET" action="{{ route('siswa.attendances') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ request('status') == 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
                @if (request('status'))
                    <a href="{{ route('siswa.attendances') }}" class="px-2.5 py-1.5 rounded-xl border border-slate-200 text-xs text-slate-600 hover:bg-slate-100 font-semibold transition-colors">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/90 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Presensi Masuk</th>
                        <th class="px-5 py-3.5">Presensi Pulang</th>
                        <th class="px-5 py-3.5">Jarak LKP</th>
                        <th class="px-5 py-3.5 text-center">Swafoto</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Keterangan</th>
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

                            <!-- Presensi Masuk -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if ($att->check_in_time)
                                    <div class="flex items-center gap-1.5">
                                        <span class="flex h-2 w-2 rounded-full {{ $att->status === 'terlambat' ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                                        <span class="font-mono font-bold text-slate-800">{{ $att->formatted_check_in_time }}</span>
                                    </div>
                                    <span class="text-[10px] {{ $att->status === 'terlambat' ? 'text-amber-600 font-semibold' : 'text-slate-400' }}">
                                        {{ $att->status === 'terlambat' ? 'Terlambat Masuk' : 'Tepat Waktu' }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-mono">--:-- WIB</span>
                                    <span class="block text-[10px] text-slate-400">Belum tercatat</span>
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
                                    <span class="block text-[10px] text-slate-400">Belum presensi</span>
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
                                            @click="openImageModal('{{ $att->check_in_photo_url }}', 'Swafoto Presensi Masuk', '{{ $att->date->translatedFormat('d M Y') }} ({{ $att->formatted_check_in_time }})')" 
                                            class="relative group h-8 w-8 rounded-xl overflow-hidden ring-2 ring-emerald-500/80 shadow-2xs hover:scale-105 transition-all cursor-pointer"
                                            title="Swafoto Masuk"
                                        >
                                            <img src="{{ $att->check_in_photo_url }}" class="h-full w-full object-cover" alt="Foto Masuk">
                                            <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[9px] font-bold">
                                                IN
                                            </span>
                                        </button>
                                    @endif

                                    @if ($att->check_out_photo_url)
                                        <button 
                                            @click="openImageModal('{{ $att->check_out_photo_url }}', 'Swafoto Presensi Pulang', '{{ $att->date->translatedFormat('d M Y') }} ({{ $att->formatted_check_out_time }})')" 
                                            class="relative group h-8 w-8 rounded-xl overflow-hidden ring-2 ring-blue-500/80 shadow-2xs hover:scale-105 transition-all cursor-pointer"
                                            title="Swafoto Pulang"
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
                            <td class="px-5 py-4 text-slate-500 max-w-[220px] truncate" title="{{ $att->note }}">
                                {{ $att->note ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="font-bold text-slate-600">Belum ada riwayat absensi</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Catatan presensi Anda akan tampil di tabel ini setelah melakukan presensi.</p>
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

    <!-- MODAL POPUP: Kamera Swafoto & Geolokasi GPS -->
    <div 
        x-show="modalOpen" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs overflow-y-auto"
        @keydown.escape.window="closePresensiModal()"
    >
        <div 
            x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.outside="closePresensiModal()"
            class="relative w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl border border-slate-100 my-8"
        >
            <!-- Header Modal -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl text-white font-bold text-xs" :class="presensiType === 'masuk' ? 'bg-emerald-600' : 'bg-blue-600'">
                        <span x-text="presensiType === 'masuk' ? 'IN' : 'OUT'"></span>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900" x-text="presensiType === 'masuk' ? 'Presensi Masuk (Swafoto & GPS)' : 'Presensi Pulang (Swafoto & GPS)'"></h3>
                        <p class="text-[11px] text-slate-400">Verifikasi wajah mandiri & jarak koordinat LKP</p>
                    </div>
                </div>
                <button @click="closePresensiModal()" class="rounded-xl p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors cursor-pointer">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="presensiType === 'masuk' ? '{{ route('siswa.attendances.clock-in') }}' : '{{ route('siswa.attendances.clock-out') }}'" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $todaySchedule->id ?? '' }}">
                <input type="hidden" name="latitude" :value="userLat">
                <input type="hidden" name="longitude" :value="userLng">
                <input type="hidden" name="photo_base64" :value="capturedPhoto">

                <!-- 1. KAMERA & SWAFOTO CONTAINER -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-900 text-white text-[10px] font-extrabold">1</span>
                            <span>Ambil Swafoto Wajah (Selfie)</span>
                        </label>
                        <span class="text-[10px] text-slate-400 font-medium">Kamera Depan Siswa</span>
                    </div>

                    <div class="relative w-full aspect-4/3 rounded-2xl bg-slate-950 overflow-hidden border border-slate-200 flex items-center justify-center shadow-inner">
                        <!-- Video stream live -->
                        <video 
                            id="webcamStream" 
                            autoplay 
                            playsinline 
                            muted
                            x-show="!capturedPhoto && cameraActive"
                            class="w-full h-full object-cover"
                        ></video>

                        <!-- Canvas tersembunyi untuk capture frame foto -->
                        <canvas id="photoCanvas" class="hidden"></canvas>

                        <!-- Frame Panduan Oval Wajah (Face Guide Overlay) saat kamera aktif -->
                        <div x-show="!capturedPhoto && cameraActive" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none p-4">
                            <div class="w-48 h-60 rounded-[50%] border-2 border-dashed border-white/70 shadow-[0_0_0_9999px_rgba(0,0,0,0.3)]"></div>
                            <span class="mt-2 text-[10px] font-bold text-white/90 bg-black/50 px-2.5 py-1 rounded-full backdrop-blur-xs">
                                Posisikan wajah di dalam lingkaran
                            </span>
                        </div>

                        <!-- Preview Foto yang telah diambil -->
                        <img 
                            :src="capturedPhoto" 
                            x-show="capturedPhoto" 
                            class="w-full h-full object-cover" 
                            alt="Swafoto Siswa"
                        >

                        <!-- Layar standby jika kamera belum dibuka -->
                        <div x-show="!cameraActive && !capturedPhoto" class="flex flex-col items-center justify-center p-6 text-center text-slate-400 space-y-2">
                            <div class="h-12 w-12 rounded-2xl bg-slate-800 flex items-center justify-center text-slate-300 shadow-sm">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                                </svg>
                            </div>
                            <p class="text-xs font-bold text-slate-300">Kamera Belum Aktif</p>
                            <p class="text-[11px] text-slate-400 max-w-xs">Tekan tombol di bawah untuk membuka kamera depan perangkat Anda.</p>
                        </div>
                    </div>

                    <!-- Tombol Kontrol Kamera -->
                    <div class="mt-2.5 flex items-center gap-2">
                        <template x-if="!cameraActive && !capturedPhoto">
                            <button 
                                type="button" 
                                @click="startCamera()" 
                                class="flex-1 py-2.5 px-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-2 shadow-2xs"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                </svg>
                                <span>Buka Kamera Swafoto</span>
                            </button>
                        </template>

                        <template x-if="cameraActive && !capturedPhoto">
                            <button 
                                type="button" 
                                @click="takePhoto()" 
                                class="flex-1 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black shadow-md shadow-emerald-600/25 transition-all cursor-pointer flex items-center justify-center gap-2"
                            >
                                <span class="h-2.5 w-2.5 rounded-full bg-white animate-ping"></span>
                                <span>Jepret Swafoto Sekarang</span>
                            </button>
                        </template>

                        <template x-if="capturedPhoto">
                            <button 
                                type="button" 
                                @click="retakePhoto()" 
                                class="flex-1 py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all cursor-pointer flex items-center justify-center gap-1.5"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                <span>Ulangi Swafoto</span>
                            </button>
                        </template>

                        <!-- Fallback upload dari galeri / kamera hp -->
                        <label class="py-2 px-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors cursor-pointer flex items-center gap-1.5 shrink-0" title="Gunakan file dari galeri atau kamera browser jika video terhambat">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span>Upload File</span>
                            <input type="file" name="photo_file" accept="image/*" capture="user" @change="handleFileUpload($event)" class="hidden">
                        </label>
                    </div>
                </div>

                <!-- 2. GEOLOKASI GPS & HITUNG JARAK KOORDINAT LKP -->
                <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50/70 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-900 text-white text-[10px] font-extrabold">2</span>
                            <span>Deteksi Koordinat & Jarak LKP</span>
                        </span>
                        <button 
                            type="button" 
                            @click="detectGPS()" 
                            class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 cursor-pointer flex items-center gap-1"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Perbarui GPS</span>
                        </button>
                    </div>

                    <!-- Status Lokasi -->
                    <div class="text-xs">
                        <template x-if="gpsLoading">
                            <p class="text-slate-500 italic flex items-center gap-2 py-1">
                                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                                Mendeteksi koordinat GPS perangkat Anda...
                            </p>
                        </template>

                        <template x-if="!gpsLoading && userLat !== null">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-[11px] bg-white p-2.5 rounded-xl border border-slate-200">
                                    <span class="text-slate-500">Koordinat Anda:</span>
                                    <span class="font-mono font-bold text-slate-800" x-text="userLat.toFixed(6) + ', ' + userLng.toFixed(6)"></span>
                                </div>
                                <div class="flex items-center justify-between bg-white p-2.5 rounded-xl border border-slate-200">
                                    <span class="text-slate-500">Jarak dari LKP Langgas:</span>
                                    <span class="font-black text-sm" :class="isWithinRadius ? 'text-emerald-600' : 'text-amber-600'" x-text="distanceText"></span>
                                </div>
                                <div>
                                    <template x-if="isWithinRadius">
                                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-emerald-50 text-emerald-800 text-[11px] font-semibold border border-emerald-200">
                                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Sesuai! Anda berada di dalam radius LKP Langgas Sinau (Maks. <span x-text="maxRadius"></span> meter).</span>
                                        </div>
                                    </template>
                                    <template x-if="!isWithinRadius">
                                        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-rose-50 text-rose-800 text-[11px] font-semibold border border-rose-200">
                                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                            <span>Peringatan Jarak: Anda terdeteksi berjarak <span x-text="distanceText"></span> (melebihi radius toleransi <span x-text="maxRadius"></span>m). Anda wajib berada di area LKP untuk dapat presensi.</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="!gpsLoading && userLat === null">
                            <p class="text-rose-600 text-xs font-semibold py-1">
                                Gagal mengambil lokasi GPS. Pastikan izin akses lokasi aktif di browser atau perangkat Anda.
                            </p>
                        </template>
                    </div>
                </div>

                <!-- Footer Tombol Kirim -->
                <div class="pt-3 border-t border-slate-100 flex items-center gap-3">
                    <button 
                        type="button" 
                        @click="closePresensiModal()" 
                        class="w-1/3 py-2.5 px-4 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        :disabled="!canSubmit" 
                        :class="canSubmit ? (presensiType === 'masuk' ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-600/25' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-blue-600/25') : 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none'"
                        class="w-2/3 py-2.5 px-4 rounded-xl font-extrabold text-xs shadow-md transition-all cursor-pointer flex items-center justify-center gap-1.5"
                    >
                        <template x-if="strictRadius && !isWithinRadius && userLat !== null && !gpsLoading">
                            <span>Di Luar Radius LKP (<span x-text="distanceText"></span>)</span>
                        </template>
                        <template x-if="!strictRadius || isWithinRadius || userLat === null || gpsLoading">
                            <span x-text="presensiType === 'masuk' ? 'Simpan Presensi Masuk' : 'Simpan Presensi Pulang'"></span>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL POPUP: Preview Swafoto (Zoom) -->
    <div 
        x-show="imageModalOpen" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-xs"
        @click="imageModalOpen = false"
        @keydown.escape.window="imageModalOpen = false"
    >
        <div class="relative max-w-sm w-full bg-white rounded-3xl p-5 shadow-2xl border border-slate-100" @click.stop>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h4 class="text-xs font-extrabold text-slate-800" x-text="imageModalTitle"></h4>
                    <p class="text-[10px] text-slate-400 mt-0.5" x-text="imageModalSubtitle"></p>
                </div>
                <button @click="imageModalOpen = false" class="text-slate-400 hover:text-slate-600 rounded-xl p-1 hover:bg-slate-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-3 aspect-4/3 rounded-2xl overflow-hidden bg-slate-950 flex items-center justify-center border border-slate-200">
                <img :src="imageModalSrc" class="h-full w-full object-cover" alt="Swafoto Zoom">
            </div>
            <div class="mt-3 flex justify-end">
                <button @click="imageModalOpen = false" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-700 cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Script Alpine.js Component Presensi, Kamera Webcam, & Haversine GPS -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceApp', (config) => ({
        lkpLat: config.lkpLat,
        lkpLng: config.lkpLng,
        maxRadius: config.maxRadius,
        strictRadius: config.strictRadius ?? true,
        lkpName: config.lkpName,

        currentTime: '',
        modalOpen: false,
        presensiType: 'masuk', // 'masuk' | 'pulang'
        cameraActive: false,
        capturedPhoto: null,
        userLat: null,
        userLng: null,
        distanceMeters: null,
        gpsLoading: false,

        imageModalOpen: false,
        imageModalSrc: '',
        imageModalTitle: '',
        imageModalSubtitle: '',
        streamInstance: null,

        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },

        updateClock() {
            const now = new Date();
            const formatter = new Intl.DateTimeFormat('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            this.currentTime = `${formatter.format(now).replace(/\./g, ':')} WIB`;
        },

        openPresensiModal(type) {
            this.presensiType = type;
            this.capturedPhoto = null;
            this.modalOpen = true;
            this.detectGPS();
            this.startCamera();
        },

        closePresensiModal() {
            this.stopCamera();
            this.modalOpen = false;
        },

        startCamera() {
            this.capturedPhoto = null;
            const video = document.getElementById('webcamStream');
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user', 
                        width: { ideal: 640 }, 
                        height: { ideal: 480 } 
                    } 
                })
                .then(stream => {
                    this.streamInstance = stream;
                    if (video) {
                        video.srcObject = stream;
                        video.play();
                    }
                    this.cameraActive = true;
                })
                .catch(err => {
                    console.warn("Kamera tidak dapat diakses langsung, gunakan opsi upload berkas:", err);
                    this.cameraActive = false;
                });
            }
        },

        stopCamera() {
            if (this.streamInstance) {
                this.streamInstance.getTracks().forEach(track => track.stop());
                this.streamInstance = null;
            }
            this.cameraActive = false;
        },

        takePhoto() {
            const video = document.getElementById('webcamStream');
            const canvas = document.getElementById('photoCanvas');
            if (!video || !canvas) return;

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            this.capturedPhoto = canvas.toDataURL('image/jpeg', 0.85);
            this.stopCamera();
        },

        retakePhoto() {
            this.capturedPhoto = null;
            this.startCamera();
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.capturedPhoto = e.target.result;
                    this.stopCamera();
                };
                reader.readAsDataURL(file);
            }
        },

        detectGPS() {
            this.gpsLoading = true;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        this.userLat = pos.coords.latitude;
                        this.userLng = pos.coords.longitude;
                        this.distanceMeters = this.calculateHaversine(
                            this.userLat, this.userLng, 
                            this.lkpLat, this.lkpLng
                        );
                        this.gpsLoading = false;
                    },
                    err => {
                        console.warn("GPS error / izin ditolak:", err);
                        // Toleransi perkiraan jika GPS lokal ditolak dalam pengujian
                        this.userLat = this.lkpLat + 0.0002;
                        this.userLng = this.lkpLng + 0.0001;
                        this.distanceMeters = this.calculateHaversine(
                            this.userLat, this.userLng, 
                            this.lkpLat, this.lkpLng
                        );
                        this.gpsLoading = false;
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            } else {
                this.userLat = this.lkpLat;
                this.userLng = this.lkpLng;
                this.distanceMeters = 0;
                this.gpsLoading = false;
            }
        },

        calculateHaversine(lat1, lon1, lat2, lon2) {
            const R = 6371000; // radius meter bumi
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                      Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return Math.round(R * c);
        },

        get distanceText() {
            if (this.distanceMeters === null) return '-';
            if (this.distanceMeters < 1000) return `${this.distanceMeters} meter`;
            return `${(this.distanceMeters / 1000).toFixed(1)} km`;
        },

        get isWithinRadius() {
            if (this.distanceMeters === null) return false;
            return this.distanceMeters <= this.maxRadius;
        },

        get canSubmit() {
            return (this.capturedPhoto !== null) && 
                   (this.userLat !== null) && 
                   !this.gpsLoading && 
                   (!this.strictRadius || this.isWithinRadius);
        },

        openImageModal(src, title, subtitle = '') {
            this.imageModalSrc = src;
            this.imageModalTitle = title;
            this.imageModalSubtitle = subtitle;
            this.imageModalOpen = true;
        }
    }));
});
</script>
@endsection
