@extends('layouts.admin', ['title' => 'Dashboard Admin', 'header' => 'Dashboard Ringkasan'])

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-indigo-800 p-6 sm:p-8 text-white shadow-lg">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-indigo-100 backdrop-blur-md">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Sistem Manajemen Akademik & Absensi
                </span>
                <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Selamat Datang di Portal Admin
                </h2>
                <p class="mt-2 text-sm text-indigo-100/90 leading-relaxed">
                    Kelola data siswa, pencatatan dan cetak rekapitulasi kehadiran per siswa, perizinan, serta penerbitan sertifikat LKP Langgas Sinau secara praktis dan terpusat.
                </p>
            </div>
            <div class="hidden sm:flex shrink-0 items-center justify-center p-3.5 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 shadow-lg">
                <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo LKP Langgas Sinau" class="h-16 w-16 object-contain drop-shadow">
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 h-64 w-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    </div>

    <!-- Quick GPS & Jam Presensi Widget -->
    @if(isset($primaryLocation) && $primaryLocation)
    <div class="rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-50/50 via-white to-slate-50 p-5 shadow-2xs">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-sm font-extrabold text-slate-900">{{ $primaryLocation->name }}</h3>
                        <span class="rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-black uppercase px-2 py-0.5">Titik GPS Aktif</span>
                        @if($primaryLocation->plus_code)
                            <span class="rounded-md bg-indigo-100 text-indigo-700 font-mono text-[10px] font-bold px-2 py-0.5">📍 {{ $primaryLocation->plus_code }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Radius Geofence: <strong>{{ $primaryLocation->radius_meters }} Meter</strong> ({{ $primaryLocation->strict_radius ? 'Kunci Ketat' : 'Toleransi' }}) &bull; 
                        Masuk: <strong>{{ $primaryLocation->in_start }} - {{ $primaryLocation->in_on_time_end }} WIB</strong> (Terlambat s/d {{ $primaryLocation->in_late_end }}) &bull; 
                        Pulang: <strong>{{ $primaryLocation->out_start }} - {{ $primaryLocation->out_end }} WIB</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.attendance-locations.index') }}" 
                   class="inline-flex items-center gap-1.5 rounded-xl bg-white border border-slate-200 px-3.5 py-2 text-xs font-bold text-slate-700 shadow-2xs hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                    <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                    </svg>
                    Pilih & Kelola Titik Peta
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- 6 KPI Stat Cards -->
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        <!-- Total Siswa -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0z" />
                </svg>
            </div>
            <p class="mt-3 text-xs font-semibold text-slate-500">Total Siswa</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $totalStudents }}</p>
        </div>

        <!-- Siswa Aktif -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mt-3 text-xs font-semibold text-slate-500">Siswa Aktif</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $activeStudents }}</p>
        </div>

        <!-- Total Presensi -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
            </div>
            <p class="mt-3 text-xs font-semibold text-slate-500">Total Presensi</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $attendanceStats['total'] }}</p>
        </div>

        <!-- Kehadiran Hari Ini -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="mt-3 text-xs font-semibold text-slate-500">Hadir Hari Ini</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $todayPresent }}</p>
        </div>

        <!-- Izin Menunggu -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 8.25h.008v.008H12v-.008z" />
                </svg>
            </div>
            <p class="mt-3 text-xs font-semibold text-slate-500">Pengajuan Izin</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $pendingPermissions }} <span class="text-xs font-normal text-amber-600">menunggu</span></p>
        </div>

        <!-- Total Sertifikat -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                </svg>
            </div>
            <p class="mt-3 text-xs font-semibold text-slate-500">Sertifikat Terbit</p>
            <p class="mt-1 text-2xl font-black text-slate-800">{{ $totalCertificates }}</p>
        </div>
    </div>

    <!-- Visual Statistik Kehadiran & Pengumuman -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Visual Kehadiran (Statistik Sederhana) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Visual Statistik Kehadiran Siswa</h3>
                    <p class="text-xs text-slate-500">Distribusi seluruh rekaman presensi akademik</p>
                </div>
                <a href="{{ route('admin.attendances.rekap') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Lihat Rekap Lengkap &rarr;
                </a>
            </div>

            <!-- Progress Bar Stacked -->
            <div class="mt-6">
                <div class="flex items-center justify-between text-xs font-bold text-slate-600 mb-2">
                    <span>Tingkat Kehadiran Global</span>
                    <span class="text-emerald-600">{{ $attendanceStats['hadir_percentage'] }}% Hadir</span>
                </div>
                <div class="h-4 w-full overflow-hidden rounded-full bg-slate-100 flex">
                    <div style="width: {{ $attendanceStats['hadir_percentage'] }}%" class="bg-emerald-500 transition-all duration-500" title="Hadir: {{ $attendanceStats['hadir_percentage'] }}%"></div>
                    <div style="width: {{ $attendanceStats['izin_percentage'] }}%" class="bg-blue-500 transition-all duration-500" title="Izin: {{ $attendanceStats['izin_percentage'] }}%"></div>
                    <div style="width: {{ $attendanceStats['sakit_percentage'] }}%" class="bg-amber-500 transition-all duration-500" title="Sakit: {{ $attendanceStats['sakit_percentage'] }}%"></div>
                    <div style="width: {{ $attendanceStats['alpa_percentage'] }}%" class="bg-rose-500 transition-all duration-500" title="Alpa: {{ $attendanceStats['alpa_percentage'] }}%"></div>
                </div>

                <!-- Legend / Key metrics -->
                <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="h-3.5 w-3.5 rounded-md bg-emerald-500 shrink-0"></span>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-500">Hadir</p>
                            <p class="text-sm font-bold text-slate-800">{{ $attendanceStats['hadir'] }} <span class="text-xs font-normal text-slate-500">({{ $attendanceStats['hadir_percentage'] }}%)</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="h-3.5 w-3.5 rounded-md bg-blue-500 shrink-0"></span>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-500">Izin</p>
                            <p class="text-sm font-bold text-slate-800">{{ $attendanceStats['izin'] }} <span class="text-xs font-normal text-slate-500">({{ $attendanceStats['izin_percentage'] }}%)</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="h-3.5 w-3.5 rounded-md bg-amber-500 shrink-0"></span>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-500">Sakit</p>
                            <p class="text-sm font-bold text-slate-800">{{ $attendanceStats['sakit'] }} <span class="text-xs font-normal text-slate-500">({{ $attendanceStats['sakit_percentage'] }}%)</span></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="h-3.5 w-3.5 rounded-md bg-rose-500 shrink-0"></span>
                        <div>
                            <p class="text-[11px] font-semibold text-slate-500">Alpa</p>
                            <p class="text-sm font-bold text-slate-800">{{ $attendanceStats['alpa'] }} <span class="text-xs font-normal text-slate-500">({{ $attendanceStats['alpa_percentage'] }}%)</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengumuman Terbaru -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-base font-bold text-slate-800">Pengumuman Terbaru</h3>
                <a href="{{ route('admin.announcements.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Semua &rarr;
                </a>
            </div>
            <div class="space-y-4 flex-1">
                @forelse ($recentAnnouncements as $item)
                    <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5">
                        <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">
                            {{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : 'Draft' }}
                        </span>
                        <h4 class="text-xs font-bold text-slate-800 mt-1 line-clamp-1">{{ $item->title }}</h4>
                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-2">{{ $item->content }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">Belum ada pengumuman.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Absensi Terbaru & Siswa Terbaru Tables -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Absensi Terbaru -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-base font-bold text-slate-800">Absensi Terbaru</h3>
                <a href="{{ route('admin.attendances.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Kelola Absensi &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">Siswa</th>
                            <th class="pb-3">Materi / Jadwal</th>
                            <th class="pb-3">Tanggal</th>
                            <th class="pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentAttendances as $att)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 font-semibold text-slate-800">
                                    {{ $att->student->user->name ?? 'Siswa' }}
                                </td>
                                <td class="py-3 text-slate-500 max-w-[150px] truncate">
                                    {{ $att->schedule->title ?? 'Sesi Pelatihan' }}
                                </td>
                                <td class="py-3 text-slate-500">
                                    {{ $att->date->translatedFormat('d/m/Y') }}
                                </td>
                                <td class="py-3 text-right">
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Siswa Terbaru -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-base font-bold text-slate-800">Siswa Terdaftar Terbaru</h3>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Semua Siswa &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold">
                            <th class="pb-3">Siswa</th>
                            <th class="pb-3">No. Siswa</th>
                            <th class="pb-3">Program</th>
                            <th class="pb-3 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentStudents as $std)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 flex items-center gap-2.5">
                                    <img src="{{ $std->photo_url }}" class="h-7 w-7 rounded-lg object-cover ring-1 ring-slate-100" alt="">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $std->user->name ?? '-' }}</p>
                                        <p class="text-[10px] text-slate-400">{{ $std->user->email ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="py-3 font-mono text-slate-600 font-medium">
                                    {{ $std->student_number }}
                                </td>
                                <td class="py-3 text-slate-600">
                                    {{ $std->class->name ?? 'Reguler' }}
                                </td>
                                <td class="py-3 text-right">
                                    @if ($std->status === 'aktif')
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 font-bold text-emerald-700 text-[10px]">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 font-bold text-slate-600 text-[10px] capitalize">{{ $std->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
