@extends('layouts.app')

@section('content')
<div class="min-h-screen lg:h-screen flex flex-col lg:grid lg:grid-cols-12 bg-slate-900 lg:overflow-hidden" x-data="{
    email: '{{ old('email') }}',
    password: '',
    showPass: false
}">

    <!-- ========================================== -->
    <!-- SECTION KIRI: BRANDING, LOGO, TAGLINE, KETERANGAN, MEDSOS -->
    <!-- ========================================== -->
    <div class="lg:col-span-6 xl:col-span-7 relative bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 lg:p-10 flex flex-col justify-between overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-800/80 lg:h-screen">
        
        <!-- Background Ambient Glow -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[450px] h-[450px] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top: Badge & Status -->
        <div class="relative z-10 shrink-0">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-slate-200 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Sistem Informasi Akademik & Presensi Siswa</span>
            </div>
        </div>

        <!-- Center: Logo, Tagline, & Deskripsi (Compact & Centered) -->
        <div class="relative z-10 my-auto py-3 max-w-xl">
            <!-- Logo & Brand Title -->
            <div class="flex items-center gap-3.5 sm:gap-4 mb-4">
                <div class="relative shrink-0">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-indigo-500 via-sky-400 to-emerald-400 rounded-2xl blur-xs opacity-70"></div>
                    <div class="relative bg-white p-2 rounded-xl shadow-xl flex items-center justify-center">
                        <img 
                            src="{{ asset('images/logo-langgas.png') }}" 
                            alt="Logo LKP Langgas Sinau" 
                            class="w-14 h-14 sm:w-16 sm:h-16 object-contain"
                        >
                    </div>
                </div>
                <div>
                    <span class="inline-block px-2.5 py-0.5 text-[10px] font-extrabold tracking-widest text-indigo-300 uppercase bg-indigo-500/20 border border-indigo-400/30 rounded-full mb-1">
                        LKP Langgas Sinau
                    </span>
                    <h1 class="text-xl sm:text-2xl xl:text-3xl font-black tracking-tight text-white uppercase leading-tight">
                        Langgas Sinau Akademi
                    </h1>
                    <p class="text-sm sm:text-base font-bold text-emerald-400 italic tracking-wide">
                        “Berdikari Mengenal Diri”
                    </p>
                </div>
            </div>

            <!-- Keterangan LKP -->
            <div class="space-y-2 text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                <p>
                    Selamat datang di portal akademik resmi <strong class="text-white font-bold">LKP Langgas Sinau</strong>. Lembaga Pelatihan & Kursus yang berfokus membangun kemandirian dan keahlian vokasi unggul.
                </p>
                <p class="text-slate-400 text-xs">
                    Platform ini mengintegrasikan presensi digital real-time, monitoring nilai berkala, dan penerbitan sertifikat kelulusan sah yang dapat diverifikasi secara transparan.
                </p>
            </div>

            <!-- Feature Pills (Compact) -->
            <div class="flex flex-wrap gap-2 pt-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-xs text-slate-300 backdrop-blur-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                    Presensi Digital Real-time
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-xs text-slate-300 backdrop-blur-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Transkrip Nilai Siswa
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-xs text-slate-300 backdrop-blur-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span>
                    Sertifikat Sah Terdaftar
                </span>
            </div>
        </div>

        <!-- Bottom: Social Media & Web Links (Always Visible Above The Fold) -->
        <div class="relative z-10 pt-3 border-t border-white/10 shrink-0">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                Kanal Resmi & Media Sosial LKP Langgas Sinau
            </p>
            <div class="flex flex-wrap items-center gap-2">
                <!-- WhatsApp -->
                <a 
                    href="https://wa.me/6281234567890?text=Halo%20Admin%20LKP%20Langgas%20Sinau,%20saya%20ingin%20bertanya%20seputar%20informasi%20pelatihan%20dan%20kursus." 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-400 border border-emerald-500/30 text-xs font-semibold transition-all hover:-translate-y-0.5 shadow-xs group"
                    title="Hubungi WhatsApp Resmi"
                >
                    <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.97.53 1.77.818 2.796.818 3.18 0 5.767-2.587 5.767-5.766.001-3.182-2.585-5.769-5.767-5.769zm10.222 5.767c0 5.642-4.59 10.231-10.222 10.231-1.777 0-3.447-.461-4.9-1.264l-5.631 1.474 1.502-5.485c-.917-1.523-1.443-3.303-1.443-5.206 0-5.643 4.59-10.232 10.222-10.232 5.642 0 10.231 4.589 10.231 10.232zm-4.321 4.195c-.234-.117-1.385-.683-1.6-.761-.215-.078-.371-.117-.528.117-.156.234-.606.761-.742.918-.137.156-.273.176-.507.059-.234-.117-.988-.364-1.882-1.161-.696-.621-1.166-1.388-1.303-1.622-.137-.234-.015-.36.103-.477.106-.105.234-.273.351-.41.117-.137.156-.234.234-.39.078-.156.039-.293-.02-.41-.059-.117-.527-1.27-.722-1.74-.19-.458-.383-.396-.527-.404l-.45-.008c-.156 0-.41.059-.625.293-.215.234-.82.801-.82 1.953s.84 2.266.957 2.422c.117.156 1.653 2.524 4.004 3.539.559.242.996.386 1.336.495.561.179 1.072.154 1.476.094.45-.067 1.385-.566 1.58-1.113.195-.547.195-1.016.137-1.113-.059-.098-.215-.156-.449-.273z"/>
                    </svg>
                    <span>WhatsApp</span>
                </a>

                <!-- Instagram -->
                <a 
                    href="https://instagram.com/langgassinau" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-pink-500/15 hover:bg-pink-500/25 text-pink-400 border border-pink-500/30 text-xs font-semibold transition-all hover:-translate-y-0.5 shadow-xs group"
                    title="Instagram @langgassinau"
                >
                    <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span>Instagram</span>
                </a>

                <!-- TikTok -->
                <a 
                    href="https://tiktok.com/@langgassinau" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700/80 text-slate-200 border border-slate-700 text-xs font-semibold transition-all hover:-translate-y-0.5 shadow-xs group"
                    title="TikTok @langgassinau"
                >
                    <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                        <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298 0 .591.044.87.13V9.4a6.33 6.33 0 0 0-.87-.06A6.34 6.34 0 0 0 3.1 15.68a6.34 6.34 0 0 0 10.82 4.48 6.27 6.27 0 0 0 1.95-4.49V8.92a8.28 8.28 0 0 0 4.84 1.54V7.02c-.38-.04-.76-.15-1.12-.33z"/>
                    </svg>
                    <span>TikTok</span>
                </a>

                <!-- Facebook -->
                <a 
                    href="https://facebook.com/langgassinau" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-500/15 hover:bg-blue-500/25 text-blue-400 border border-blue-500/30 text-xs font-semibold transition-all hover:-translate-y-0.5 shadow-xs group"
                    title="Facebook LKP Langgas Sinau"
                >
                    <svg class="w-4 h-4 fill-current transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Facebook</span>
                </a>

                <!-- Web Resmi LKP -->
                <a 
                    href="https://langgas-sinau.com" 
                    target="_blank" 
                    rel="noopener noreferrer" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-indigo-500/15 hover:bg-indigo-500/25 text-indigo-300 border border-indigo-500/30 text-xs font-semibold transition-all hover:-translate-y-0.5 shadow-xs group"
                    title="Website Resmi LKP Langgas Sinau"
                >
                    <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                    <span>Web Resmi LKP</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SECTION KANAN: FORM LOGIN & LUPA SANDI -->
    <!-- ========================================== -->
    <div class="lg:col-span-6 xl:col-span-5 bg-slate-50 flex flex-col justify-between p-6 sm:p-8 lg:p-10 min-h-screen lg:h-screen lg:overflow-y-auto">
        
        <!-- Mobile Header (Visible on Mobile / Small screens only) -->
        <div class="lg:hidden flex items-center gap-3 pb-4 border-b border-slate-200">
            <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo LKP Langgas Sinau" class="w-10 h-10 object-contain">
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase">Langgas Sinau Akademi</h3>
                <p class="text-xs text-emerald-600 font-bold italic">“Berdikari Mengenal Diri”</p>
            </div>
        </div>

        <div class="w-full max-w-md mx-auto my-auto py-4">
            <!-- Form Card Header -->
            <div class="mb-5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-2">
                    <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span>Autentikasi Portal</span>
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    Masuk ke Akun Anda
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-slate-500">
                    Masukkan alamat email dan kata sandi untuk mengakses dashboard.
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl p-6 sm:p-7 shadow-xl shadow-slate-200/60 border border-slate-100">
                <!-- Notifications / Alerts -->
                <x-alert />

                <form class="space-y-4" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                autofocus
                                x-model="email"
                                placeholder="nama@langgas-sinau.com" 
                                class="block w-full rounded-xl border border-slate-300 pl-11 pr-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-colors"
                            >
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kata Sandi / Password
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input 
                                id="password" 
                                name="password" 
                                :type="showPass ? 'text' : 'password'" 
                                autocomplete="current-password" 
                                required 
                                x-model="password"
                                placeholder="••••••••" 
                                class="block w-full rounded-xl border border-slate-300 pl-11 pr-11 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 transition-colors"
                            >
                            <!-- Show/Hide Password Toggle with Alpine.js -->
                            <button 
                                type="button" 
                                @click="showPass = !showPass" 
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors"
                                :title="showPass ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'"
                            >
                                <svg x-show="!showPass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="showPass" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Lupa Sandi Link ke WhatsApp -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500/20">
                            <span class="text-xs text-slate-600 font-medium">Ingat saya</span>
                        </label>

                        <!-- Link Lupa Sandi Mengarah ke WhatsApp -->
                        <a 
                            href="https://wa.me/6281234567890?text=Halo%20Admin%20LKP%20Langgas%20Sinau,%20saya%20lupa%20kata%20sandi%20akun%20saya.%20Mohon%20bantuan%20untuk%20reset%20kata%20sandi." 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors"
                            title="Hubungi Admin via WhatsApp untuk reset kata sandi"
                        >
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.97.53 1.77.818 2.796.818 3.18 0 5.767-2.587 5.767-5.766.001-3.182-2.585-5.769-5.767-5.769zm10.222 5.767c0 5.642-4.59 10.231-10.222 10.231-1.777 0-3.447-.461-4.9-1.264l-5.631 1.474 1.502-5.485c-.917-1.523-1.443-3.303-1.443-5.206 0-5.643 4.59-10.232 10.222-10.232 5.642 0 10.231 4.589 10.231 10.232zm-4.321 4.195c-.234-.117-1.385-.683-1.6-.761-.215-.078-.371-.117-.528.117-.156.234-.606.761-.742.918-.137.156-.273.176-.507.059-.234-.117-.988-.364-1.882-1.161-.696-.621-1.166-1.388-1.303-1.622-.137-.234-.015-.36.103-.477.106-.105.234-.273.351-.41.117-.137.156-.234.234-.39.078-.156.039-.293-.02-.41-.059-.117-.527-1.27-.722-1.74-.19-.458-.383-.396-.527-.404l-.45-.008c-.156 0-.41.059-.625.293-.215.234-.82.801-.82 1.953s.84 2.266.957 2.422c.117.156 1.653 2.524 4.004 3.539.559.242.996.386 1.336.495.561.179 1.072.154 1.476.094.45-.067 1.385-.566 1.58-1.113.195-.547.195-1.016.137-1.113-.059-.098-.215-.156-.449-.273z"/>
                            </svg>
                            <span>Lupa Sandi?</span>
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1.5">
                        <button 
                            type="submit" 
                            class="flex w-full justify-center items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-indigo-800 py-2.5 px-4 text-sm font-bold text-white shadow-md shadow-indigo-600/30 hover:from-indigo-500 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all active:scale-[0.99] cursor-pointer"
                        >
                            <span>Masuk ke Akun</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Mobile Contact Note -->
            <div class="mt-4 text-center text-xs text-slate-500">
                Mengalami kendala login? <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mengalami%20kendala%20login%20ke%20Akademi%20Langgas%20Sinau" target="_blank" rel="noopener noreferrer" class="font-bold text-emerald-600 hover:underline">Hubungi Bantuan</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-400 py-3 border-t border-slate-200/80 shrink-0">
            &copy; {{ date('Y') }} LKP Langgas Sinau. All rights reserved.
        </div>
    </div>
</div>
@endsection
