<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200/80 bg-white/95 px-4 sm:px-6 backdrop-blur-md">
    <!-- Left: Mobile menu button & breadcrumbs -->
    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
        <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 lg:hidden focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
            title="Buka Menu"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Mobile Logo -->
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('siswa.dashboard') }}" class="lg:hidden flex items-center shrink-0">
            <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo Langgas Sinau" class="h-8 w-8 object-contain">
        </a>

        <div class="flex flex-col">
            <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-semibold leading-none mb-1">
                <span>LKP Langgas Sinau</span>
                <span>/</span>
                <span class="text-indigo-600 capitalize">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Siswa' }}</span>
            </div>
            <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-none">
                {{ $title ?? 'Dashboard' }}
            </h1>
        </div>
    </div>

    <!-- Right: Jakarta Clock & User Profile Dropdown -->
    <div class="flex items-center gap-2.5 sm:gap-3">
        <!-- Live Jakarta (WIB) Clock -->
        <div 
            x-data="{
                timeWIB: '',
                updateTime() {
                    const now = new Date();
                    const formatter = new Intl.DateTimeFormat('id-ID', {
                        timeZone: 'Asia/Jakarta',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });
                    this.timeWIB = formatter.format(now).replace(/\./g, ':');
                },
                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                }
            }"
            class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs shadow-2xs"
            title="Waktu Indonesia Barat (WIB) - Jakarta"
        >
            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-mono font-bold text-slate-800 tracking-tight" x-text="timeWIB">--:--:--</span>
            <span class="text-[10px] font-extrabold text-indigo-700 bg-indigo-100/70 px-1.5 py-0.5 rounded-md">WIB</span>
        </div>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button 
                @click="open = !open" 
                class="flex items-center gap-2.5 rounded-2xl p-1.5 text-slate-600 hover:bg-slate-100 border border-transparent hover:border-slate-200 transition-all focus:outline-none cursor-pointer"
            >
                @if (auth()->user()->role === 'siswa' && auth()->user()->student && auth()->user()->student->photo_url)
                    <img class="h-8 w-8 rounded-xl object-cover ring-1 ring-slate-200" src="{{ auth()->user()->student->photo_url }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-700 font-black text-white text-xs shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                @endif
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-xs font-bold text-slate-800 line-clamp-1 leading-snug">{{ auth()->user()->name }}</span>
                    <span class="text-[10px] text-slate-500 font-medium capitalize">{{ auth()->user()->role }}</span>
                </div>
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-cloak
                class="absolute right-0 mt-2 w-60 origin-top-right rounded-2xl bg-white p-2 shadow-2xl ring-1 ring-slate-900/10 focus:outline-none z-50 border border-slate-100"
            >
                <div class="px-3 py-2.5 border-b border-slate-100 bg-slate-50/50 rounded-xl mb-1">
                    <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ auth()->user()->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ auth()->user()->role }} LKP Langgas
                    </span>
                </div>

                <div class="py-1 space-y-0.5">
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07" />
                            </svg>
                            Data Siswa
                        </a>
                        <a href="{{ route('admin.attendances.rekap') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18" />
                            </svg>
                            Cetak Hasil Absensi
                        </a>
                        <a href="{{ route('admin.certificates.index') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375" />
                            </svg>
                            Nilai & Sertifikat
                        </a>
                    @else
                        <a href="{{ route('siswa.profile') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-emerald-600 transition-colors">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            Profil Saya
                        </a>
                    @endif
                </div>

                <div class="border-t border-slate-100 pt-1 mt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer">
                            <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            Keluar / Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
