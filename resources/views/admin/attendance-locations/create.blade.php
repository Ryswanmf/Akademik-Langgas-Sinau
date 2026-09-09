@extends('layouts.admin', ['title' => 'Tambah Titik GPS', 'header' => 'Tambah Titik GPS & Waktu Presensi'])

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-container {
        font-family: inherit;
        z-index: 10 !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="locationForm({
    initialLat: {{ old('latitude', $defaultLocation['latitude']) }},
    initialLng: {{ old('longitude', $defaultLocation['longitude']) }},
    initialRadius: {{ old('radius_meters', $defaultLocation['radius_meters']) }},
    initialPlusCode: '{{ addslashes(old('plus_code', $defaultLocation['plus_code'])) }}'
})">

    <!-- Top Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.attendance-locations.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali ke Titik GPS
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs text-slate-500 font-medium">Tambah Baru</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">Tambah Titik GPS & Pengaturan Waktu</h1>
            <p class="text-xs text-slate-500 mt-0.5">Tentukan titik koordinat langsung dari peta serta atur toleransi radius dan batas jam absensi.</p>
        </div>
    </div>

    <!-- Main Form Grid -->
    <form method="POST" action="{{ route('admin.attendance-locations.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf

        <!-- Kolom Kiri: Form Input Fields (7 Kolom) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Card 1: Identitas & Lokasi -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-2xs space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Identitas Lokasi Titik GPS</h2>
                        <p class="text-xs text-slate-500">Nama tempat dan alamat resmi lokasi presensi</p>
                    </div>
                </div>

                <!-- Nama Lokasi -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Titik / Kampus <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $defaultLocation['name']) }}" required
                           placeholder="Contoh: LKP Langgas Sinau (Kampus Utama Banjar Rejo)"
                           class="w-full rounded-xl border @error('name') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/50 @enderror px-3.5 py-2.5 text-xs text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition-all">
                    @error('name')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Google Plus Code -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="plus_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Google Plus Code
                        </label>
                        <button type="button" @click="setBanjarRejoPlusCode()" class="text-[11px] text-indigo-600 hover:text-indigo-800 font-bold transition-colors">
                            Pakai Kode Banjar Rejo
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" id="plus_code" name="plus_code" x-model="plusCode"
                               placeholder="Contoh: V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-mono text-indigo-700 placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Kode alfanumerik lokasi Google Maps (Open Location Code).</p>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2" required
                              placeholder="Masukkan alamat jalan, kelurahan/desa, kecamatan, dan kabupaten..."
                              class="w-full rounded-xl border @error('address') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/50 @enderror px-3.5 py-2.5 text-xs text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition-all">{{ old('address', $defaultLocation['address']) }}</textarea>
                    @error('address')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Card 2: Koordinat & Geofencing Radius -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-2xs space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Koordinat & Radius Geofence</h2>
                        <p class="text-xs text-slate-500">Nilai latitude, longitude, dan batas jarak maksimal presensi siswa</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Latitude -->
                    <div>
                        <label for="latitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Latitude <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.0000001" id="latitude" name="latitude" x-model.number="lat" @input="updateMapFromInputs()" required
                               placeholder="-5.124188"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition-all">
                        @error('latitude')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Longitude -->
                    <div>
                        <label for="longitude" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Longitude <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.0000001" id="longitude" name="longitude" x-model.number="lng" @input="updateMapFromInputs()" required
                               placeholder="105.332312"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs font-mono font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition-all">
                        @error('longitude')
                            <p class="text-[11px] text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Radius Slider & Input -->
                <div class="space-y-2 pt-2 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <label for="radius_meters" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Radius Toleransi Presensi <span class="text-rose-500">*</span>
                        </label>
                        <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-extrabold text-indigo-700">
                            <span x-text="radius">150</span> Meter
                        </span>
                    </div>

                    <div class="flex items-center gap-4">
                        <input type="range" min="20" max="1000" step="10" x-model.number="radius" @input="updateCircleRadius()"
                               class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <input type="number" min="10" max="50000" id="radius_meters" name="radius_meters" x-model.number="radius" @input="updateCircleRadius()" required
                               class="w-24 rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 text-center focus:bg-white focus:border-indigo-600 focus:outline-none">
                    </div>
                    <p class="text-[11px] text-slate-400">Siswa yang berada di luar jarak lingkaran ini akan ditolak atau diberi peringatan saat presensi.</p>
                </div>

                <!-- Kunci Radius GPS Ketat -->
                <div class="pt-3 border-t border-slate-100 flex items-start gap-3">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" id="strict_radius" name="strict_radius" value="1" {{ old('strict_radius', $defaultLocation['strict_radius']) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label for="strict_radius" class="text-xs font-bold text-slate-900 cursor-pointer">
                            Kunci Radius GPS Ketat (Wajib Berada di Lokasi)
                        </label>
                        <p class="text-[11px] text-slate-500">Jika dicentang, sistem otomatis menolak presensi siswa apabila terdeteksi berada di luar radius meter yang ditentukan.</p>
                    </div>
                </div>
            </div>

            <!-- Card 3: Aturan Waktu Presensi Masuk & Pulang -->
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-2xs space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Aturan Waktu Absensi</h2>
                        <p class="text-xs text-slate-500">Tentukan jam buka, batas tepat waktu, dan toleransi keterlambatan</p>
                    </div>
                </div>

                <!-- Jam Masuk Section -->
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg inline-block mb-3">
                        Presensi Masuk
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label for="in_start" class="block text-[11px] font-bold text-slate-600 mb-1">Jam Mulai Masuk</label>
                            <input type="time" id="in_start" name="in_start" value="{{ old('in_start', $defaultLocation['in_start']) }}" required
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Pintu dibuka</span>
                        </div>
                        <div>
                            <label for="in_on_time_end" class="block text-[11px] font-bold text-slate-600 mb-1">Batas Tepat Waktu</label>
                            <input type="time" id="in_on_time_end" name="in_on_time_end" value="{{ old('in_on_time_end', $defaultLocation['in_on_time_end']) }}" required
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Dicatat Hadir</span>
                        </div>
                        <div>
                            <label for="in_late_end" class="block text-[11px] font-bold text-slate-600 mb-1">Batas Terlambat</label>
                            <input type="time" id="in_late_end" name="in_late_end" value="{{ old('in_late_end', $defaultLocation['in_late_end']) }}" required
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Lewat: Ditutup</span>
                        </div>
                    </div>
                </div>

                <!-- Jam Pulang Section -->
                <div class="pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg inline-block mb-3">
                        Presensi Kepulangan
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label for="out_start" class="block text-[11px] font-bold text-slate-600 mb-1">Jam Buka Pulang</label>
                            <input type="time" id="out_start" name="out_start" value="{{ old('out_start', $defaultLocation['out_start']) }}" required
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Mulai boleh pulang</span>
                        </div>
                        <div>
                            <label for="out_end" class="block text-[11px] font-bold text-slate-600 mb-1">Batas Akhir Pulang</label>
                            <input type="time" id="out_end" name="out_end" value="{{ old('out_end', $defaultLocation['out_end']) }}" required
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Jam tutup absensi</span>
                        </div>
                        <div>
                            <label for="auto_alpa_time" class="block text-[11px] font-bold text-slate-600 mb-1">Batas Auto-Alpa</label>
                            <input type="time" id="auto_alpa_time" name="auto_alpa_time" value="{{ old('auto_alpa_time', $defaultLocation['auto_alpa_time']) }}" required
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-xs font-bold text-slate-900 focus:bg-white focus:border-indigo-600 focus:outline-none">
                            <span class="text-[10px] text-slate-400">Eksekusi Alpa otomatis</span>
                        </div>
                    </div>
                </div>

                <!-- Hari Kerja Checkboxes -->
                <div class="pt-4 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Hari Operasional Pelatihan
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @php
                            $selectedDays = old('working_days', $defaultLocation['working_days']);
                            $daysMap = [
                                1 => 'Senin',
                                2 => 'Selasa',
                                3 => 'Rabu',
                                4 => 'Kamis',
                                5 => 'Jumat',
                                6 => 'Sabtu',
                                0 => 'Minggu',
                            ];
                        @endphp
                        @foreach($daysMap as $code => $name)
                            <label class="flex items-center gap-2 p-2 rounded-xl border border-slate-200 bg-slate-50/40 hover:bg-indigo-50/40 cursor-pointer text-xs font-medium text-slate-700 transition-colors">
                                <input type="checkbox" name="working_days[]" value="{{ $code }}" 
                                       {{ in_array($code, $selectedDays) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                                <span>{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="pt-3 border-t border-slate-100 flex items-start gap-3">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $defaultLocation['is_active']) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                    </div>
                    <div>
                        <label for="is_active" class="text-xs font-bold text-slate-900 cursor-pointer">
                            Aktifkan Titik GPS Ini Segera
                        </label>
                        <p class="text-[11px] text-slate-500">Titik yang aktif langsung menjadi acuan geofencing bagi siswa yang presensi mandiri.</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.attendance-locations.index') }}" 
                   class="rounded-xl border border-slate-200 px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Simpan Titik GPS & Aturan
                </button>
            </div>
        </div>

        <!-- Kolom Kanan: Interactive Map Picker (5 Kolom) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="sticky top-6 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Pilih Titik Langsung dari Peta</h2>
                        <p class="text-[11px] text-slate-500">Klik peta atau geser pin merah untuk menggeser lokasi</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase px-2 py-0.5 border border-emerald-200">
                        Interactive
                    </span>
                </div>

                <!-- Tombol Cepat Pilihan -->
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="setBanjarRejoCoordinates()" 
                            class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-200 bg-indigo-50/80 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-all shadow-2xs">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Set ke V8GJ+8W Banjar Rejo
                    </button>

                    <button type="button" @click="getCurrentGps()" 
                            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-2xs">
                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        GPS Saya
                    </button>
                </div>

                <!-- Input Pencarian Tempat / Alamat OSM -->
                <div class="relative">
                    <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchPlace()"
                           placeholder="Ketik nama tempat/desa lalu tekan Enter..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50/50 pl-3.5 pr-16 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none">
                    <button type="button" @click="searchPlace()" 
                            class="absolute right-1 top-1 bottom-1 px-2.5 rounded-lg bg-indigo-600 text-white text-[11px] font-bold hover:bg-indigo-700 transition-colors">
                        Cari
                    </button>
                </div>

                <!-- Leaflet Interactive Canvas -->
                <div class="relative w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                    <div id="picker-map" class="w-full h-full"></div>
                </div>

                <!-- Live Coordinate Badges -->
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center justify-between text-[11px] font-mono">
                    <span class="text-slate-500">Titik Terpilih:</span>
                    <span class="font-bold text-slate-900" x-text="lat.toFixed(6) + ', ' + lng.toFixed(6)"></span>
                </div>

                <div class="p-3 rounded-xl bg-indigo-50/60 border border-indigo-100 text-[11px] text-indigo-700 leading-relaxed">
                    💡 <strong>Tips Memilih Titik:</strong> Klik di manapun pada peta atau seret marker berikon pin merah untuk menentukan posisi persis gerbang atau kantor LKP Langgas Sinau.
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
function locationForm(config) {
    return {
        lat: config.initialLat,
        lng: config.initialLng,
        radius: config.initialRadius,
        plusCode: config.initialPlusCode,
        searchQuery: '',
        map: null,
        marker: null,
        circle: null,

        init() {
            this.$nextTick(() => {
                this.initMap();
            });
        },

        initMap() {
            if (!document.getElementById('picker-map')) return;

            this.map = L.map('picker-map', {
                center: [this.lat, this.lng],
                zoom: 17,
                zoomControl: true,
                scrollWheelZoom: true
            });

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(this.map);

            // Custom draggable pin
            const markerIcon = L.divIcon({
                className: 'custom-picker-icon',
                html: `<div style="background-color:#ef4444; width:34px; height:34px; border-radius:50% 50% 50% 0; transform:rotate(-45deg); border:3px solid #ffffff; box-shadow:0 6px 12px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center;">
                         <div style="width:10px; height:10px; background-color:white; border-radius:50%; transform:rotate(45deg);"></div>
                       </div>`,
                iconSize: [34, 34],
                iconAnchor: [17, 34],
                popupAnchor: [0, -34]
            });

            this.marker = L.marker([this.lat, this.lng], {
                draggable: true,
                icon: markerIcon
            }).addTo(this.map);

            this.circle = L.circle([this.lat, this.lng], {
                radius: this.radius,
                color: '#4f46e5',
                fillColor: '#6366f1',
                fillOpacity: 0.2,
                weight: 2,
                dashArray: '5, 5'
            }).addTo(this.map);

            // Event saat drag marker
            this.marker.on('drag', (e) => {
                const pos = e.target.getLatLng();
                this.lat = parseFloat(pos.lat.toFixed(7));
                this.lng = parseFloat(pos.lng.toFixed(7));
                this.circle.setLatLng(pos);
            });

            // Event saat klik pada peta
            this.map.on('click', (e) => {
                const pos = e.latlng;
                this.lat = parseFloat(pos.lat.toFixed(7));
                this.lng = parseFloat(pos.lng.toFixed(7));
                this.marker.setLatLng(pos);
                this.circle.setLatLng(pos);
            });

            setTimeout(() => {
                this.map.invalidateSize();
            }, 300);
        },

        updateMapFromInputs() {
            if (!this.map || !this.marker) return;
            const newLatLng = [this.lat, this.lng];
            this.marker.setLatLng(newLatLng);
            this.circle.setLatLng(newLatLng);
            this.map.panTo(newLatLng);
        },

        updateCircleRadius() {
            if (this.circle) {
                this.circle.setRadius(this.radius);
            }
        },

        setBanjarRejoCoordinates() {
            this.lat = -5.124188;
            this.lng = 105.332312;
            this.plusCode = 'V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung';
            if (document.getElementById('name') && !document.getElementById('name').value) {
                document.getElementById('name').value = 'LKP Langgas Sinau (Kampus Utama Banjar Rejo)';
            }
            if (document.getElementById('address') && !document.getElementById('address').value) {
                document.getElementById('address').value = 'Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung';
            }
            this.updateMapFromInputs();
            this.map.setView([this.lat, this.lng], 17);
        },

        setBanjarRejoPlusCode() {
            this.plusCode = 'V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung';
        },

        getCurrentGps() {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung deteksi lokasi GPS.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.lat = parseFloat(pos.coords.latitude.toFixed(7));
                    this.lng = parseFloat(pos.coords.longitude.toFixed(7));
                    this.updateMapFromInputs();
                    this.map.setView([this.lat, this.lng], 18);
                },
                (err) => {
                    alert('Gagal mengambil lokasi GPS: ' + err.message);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        async searchPlace() {
            if (!this.searchQuery.trim()) return;

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                if (data && data.length > 0) {
                    const first = data[0];
                    this.lat = parseFloat(parseFloat(first.lat).toFixed(7));
                    this.lng = parseFloat(parseFloat(first.lon).toFixed(7));
                    this.updateMapFromInputs();
                    this.map.setView([this.lat, this.lng], 17);

                    if (!document.getElementById('address').value) {
                        document.getElementById('address').value = first.display_name;
                    }
                } else {
                    alert('Lokasi tidak ditemukan. Coba gunakan nama desa/kecamatan atau seret pin peta.');
                }
            } catch (e) {
                alert('Gagal mencari lokasi. Silakan pilih langsung di peta.');
            }
        }
    };
}
</script>
@endpush
