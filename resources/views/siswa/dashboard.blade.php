@extends('layouts.siswa', ['title' => 'Dashboard Siswa', 'header' => 'Dashboard Akademik'])

@section('content')
<div class="space-y-8">
    <!-- Student Profile Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 relative z-10">
            <div class="flex items-center gap-4 sm:gap-5">
                <img src="{{ $student->photo_url }}" class="h-18 w-18 sm:h-20 sm:w-20 rounded-2xl object-cover ring-4 ring-white/20 shadow-md shrink-0" alt="{{ $student->user->name }}">
                <div class="space-y-1">
                    <span class="inline-flex rounded-full bg-white/15 px-3 py-0.5 text-xs font-semibold text-emerald-100 backdrop-blur-md">
                        {{ $student->program }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight">
                        {{ $student->user->name }}
                    </h2>
                    <p class="text-xs text-emerald-100/90 font-mono">
                        NIS: {{ $student->student_number }} • Kelas: <span class="font-bold text-white">{{ $student->class->name ?? 'Belum ada kelas' }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('siswa.attendances') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white text-emerald-800 font-extrabold text-xs shadow-md hover:bg-emerald-50 transition-all">
                    <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                    </svg>
                    <span>Presensi Sekarang (Swafoto & GPS)</span>
                </a>
                <span class="inline-flex items-center gap-1.5 rounded-2xl bg-emerald-500/20 px-3 py-2 text-xs font-bold text-emerald-200 border border-emerald-400/30">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    <span class="capitalize text-white ml-1">{{ $student->status }}</span>
                </span>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 h-48 w-48 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
    </div>

    <!-- Attendance Stats Cards -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Statistik Kehadiran Saya</h3>
            <a href="{{ route('siswa.attendances') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800">
                Detail Absensi &rarr;
            </a>
        </div>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
            <!-- Persentase Kehadiran -->
            <div class="col-span-2 sm:col-span-1 rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-xs">
                <p class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Tingkat Kehadiran</p>
                <p class="mt-1 text-2xl sm:text-3xl font-black text-emerald-700">{{ $stats['rate'] }}%</p>
                <p class="text-[10px] text-emerald-600 mt-1 font-semibold">{{ $stats['hadir'] }} dari {{ $stats['total'] }} sesi</p>
            </div>

            <!-- Hadir -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hadir</p>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['hadir'] }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Pertemuan</p>
            </div>

            <!-- Izin -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Izin</p>
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                </div>
                <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['izin'] }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Pertemuan</p>
            </div>

            <!-- Sakit -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sakit</p>
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                </div>
                <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['sakit'] }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Pertemuan</p>
            </div>

            <!-- Alpa -->
            <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alpa</p>
                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                </div>
                <p class="mt-1 text-2xl font-black text-slate-800">{{ $stats['alpa'] }}</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Pertemuan</p>
            </div>
        </div>
    </div>

    <!-- Jadwal Terdekat & Pengumuman Terbaru -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Jadwal Terdekat -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Jadwal Kelas Terdekat</h3>
                </div>
                <a href="{{ route('siswa.schedules') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Semua Jadwal &rarr;
                </a>
            </div>

            <div class="space-y-3 flex-1">
                @forelse ($upcomingSchedules as $sched)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4 flex items-start justify-between gap-3">
                        <div>
                            <span class="inline-flex rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-bold px-2 py-0.5 mb-1.5">
                                {{ $sched->date->translatedFormat('l, d M Y') }}
                            </span>
                            <h4 class="text-xs font-bold text-slate-800">{{ $sched->title }}</h4>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                Ruangan: <span class="font-semibold text-slate-700">{{ $sched->room }}</span> • Waktu: <span class="font-mono text-slate-700">{{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }} WIB</span>
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-xs text-slate-400">
                        Tidak ada jadwal terdekat saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pengumuman Terbaru -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.213m3.102 0a41.442 41.442 0 007.82 2.658c.84.184 1.64-.442 1.64-1.303V5.424c0-.861-.8-1.487-1.64-1.303a41.439 41.439 0 00-7.82 2.658m0 9.18V6.84" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Pengumuman Terbaru</h3>
                </div>
                <a href="{{ route('siswa.announcements') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="space-y-3 flex-1">
                @forelse ($recentAnnouncements as $ann)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-4">
                        <span class="text-[10px] font-bold text-emerald-600 uppercase">
                            {{ $ann->published_at ? $ann->published_at->translatedFormat('d M Y') : 'Pengumuman' }}
                        </span>
                        <h4 class="text-xs font-bold text-slate-800 mt-1">{{ $ann->title }}</h4>
                        <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $ann->content }}</p>
                        <a href="{{ route('siswa.announcements.show', $ann) }}" class="inline-block mt-2 text-[11px] font-bold text-indigo-600 hover:underline">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                @empty
                    <div class="py-10 text-center text-xs text-slate-400">
                        Belum ada pengumuman baru.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
