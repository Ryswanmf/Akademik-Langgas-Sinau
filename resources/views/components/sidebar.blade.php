@props(['role' => 'admin'])

<!-- Mobile Sidebar Backdrop -->
<div 
    x-show="sidebarOpen" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden"
    @click="sidebarOpen = false"
    x-cloak
></div>

<!-- Sidebar Container -->
<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200/80 bg-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
>
    <!-- Brand Header -->
    <div class="flex h-20 items-center justify-between px-6 border-b border-slate-100">
        <a href="{{ $role === 'admin' ? route('admin.dashboard') : route('siswa.dashboard') }}" class="flex items-center gap-3 group">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-50 p-1.5 border border-slate-200/80 shadow-2xs group-hover:scale-105 transition-transform">
                <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo Langgas Sinau" class="h-full w-full object-contain">
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-extrabold tracking-tight text-slate-900 leading-tight">LANGGAS SINAU</span>
                <span class="text-[10px] font-bold tracking-wider text-indigo-600 uppercase">AKADEMI</span>
                <span class="text-[9px] text-slate-500 italic">“Berdikari Mengenal Diri”</span>
            </div>
        </a>

        <!-- Mobile close button -->
        <button @click="sidebarOpen = false" class="rounded-lg p-1 text-slate-400 hover:text-slate-600 lg:hidden">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Items -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-1.5 scrollbar-thin">
        @if ($role === 'admin')
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Menu Utama
            </div>

            <!-- Admin: Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard
            </a>

            <!-- Admin: Data Siswa -->
            <a href="{{ route('admin.students.index') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('admin.students.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                Data Siswa
            </a>

            <div class="px-3 pt-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Presensi & Absensi Siswa
            </div>

            <!-- Admin: Absensi (Fitur Utama) -->
            <a href="{{ route('admin.attendances.index') }}" 
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('admin.attendances.index', 'admin.attendances.create', 'admin.attendances.edit') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Absensi Siswa
                </div>
                <span class="rounded-md bg-indigo-500/20 text-[10px] px-1.5 py-0.5 font-bold uppercase tracking-wider {{ request()->routeIs('admin.attendances.index', 'admin.attendances.create', 'admin.attendances.edit') ? 'text-white' : 'text-indigo-600' }}">Utama</span>
            </a>

            <!-- Admin: Rekap & Cetak Hasil Absensi -->
            <a href="{{ route('admin.attendances.rekap') }}" 
               class="flex items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('admin.attendances.rekap', 'admin.attendances.print-student') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                    </svg>
                    Cetak Hasil Absensi
                </div>
                <span class="rounded-md bg-emerald-500/20 text-[10px] px-1.5 py-0.5 font-bold uppercase tracking-wider {{ request()->routeIs('admin.attendances.rekap', 'admin.attendances.print-student') ? 'text-white' : 'text-emerald-700' }}">Cetak</span>
            </a>

            <!-- Admin: Perizinan -->
            <a href="{{ route('admin.permissions.index') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('admin.permissions.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.09 1.976 1.052 1.976 2.188V8.25M8.25 8.25H18M8.25 8.25v10.5c0 1.242 1.008 2.25 2.25 2.25h7c1.242 0 2.25-1.008 2.25-2.25V8.25" />
                </svg>
                Perizinan Siswa
            </a>

            <div class="px-3 pt-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Penilaian & Sertifikasi
            </div>

            <!-- Admin: Nilai & Sertifikat -->
            <a href="{{ route('admin.certificates.index') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('admin.certificates.*') ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                </svg>
                Nilai & Sertifikat
            </a>

        @else
            <!-- Siswa Sidebar Navigation -->
            <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                Menu Siswa
            </div>

            <!-- Siswa: Dashboard -->
            <a href="{{ route('siswa.dashboard') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('siswa.dashboard') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Dashboard
            </a>

            <!-- Siswa: Profil Saya -->
            <a href="{{ route('siswa.profile') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('siswa.profile') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Profil Saya
            </a>

            <!-- Siswa: Absensi Saya -->
            <a href="{{ route('siswa.attendances') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('siswa.attendances') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Absensi Saya
            </a>

            <!-- Siswa: Pengajuan Izin -->
            <a href="{{ route('siswa.permissions') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('siswa.permissions') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pengajuan Izin
            </a>

            <!-- Siswa: Nilai & Sertifikat -->
            <a href="{{ route('siswa.certificates') }}" 
               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs font-semibold transition-colors {{ request()->routeIs('siswa.certificates') ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                </svg>
                Nilai & Sertifikat
            </a>
        @endif
    </div>

    <!-- User Mini Profile Footer -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-200 text-slate-700 font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-600 truncate capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
