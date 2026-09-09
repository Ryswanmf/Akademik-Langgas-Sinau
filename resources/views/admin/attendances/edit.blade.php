@extends('layouts.admin', ['title' => 'Ubah Absensi', 'header' => 'Ubah Data Presensi'])

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
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Ubah Data Presensi Siswa</h2>
            <p class="text-xs text-slate-500">Koreksi status, jam kehadiran, dan sesuaikan titik lokasi koordinat GPS di peta.</p>
        </div>
        <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <!-- Student Info Header -->
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <img src="{{ $attendance->student->photo_url }}" class="h-12 w-12 rounded-2xl object-cover ring-2 ring-slate-200/60" alt="{{ $attendance->student->user->name }}">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">{{ $attendance->student->user->name }}</h3>
                    <p class="text-xs text-slate-500 font-mono">{{ $attendance->student->student_number }} • {{ $attendance->student->program ?: ($attendance->student->school_origin ?: 'Siswa LKP') }}</p>
                </div>
            </div>
            <div class="text-right sm:border-l sm:border-slate-200/80 sm:pl-4">
                <span class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Tanggal Presensi</span>
                <span class="text-xs font-bold text-slate-800">{{ $attendance->date->translatedFormat('l, d F Y') }}</span>
                @if($attendance->schedule)
                    <p class="text-[11px] text-indigo-600 font-semibold">{{ $attendance->schedule->title }}</p>
                @endif
            </div>
        </div>

        <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST" class="space-y-5" id="form-edit-attendance">
            @csrf
            @method('PUT')

            <!-- Status Kehadiran -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Status Kehadiran *</label>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-xs font-semibold">
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-emerald-50 has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:text-emerald-800 transition-all">
                        <input type="radio" name="status" value="hadir" {{ old('status', $attendance->status) === 'hadir' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <span>Hadir</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-amber-50 has-checked:border-amber-500 has-checked:bg-amber-50 has-checked:text-amber-800 transition-all">
                        <input type="radio" name="status" value="terlambat" {{ old('status', $attendance->status) === 'terlambat' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                        <span>Terlambat</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-blue-50 has-checked:border-blue-500 has-checked:bg-blue-50 has-checked:text-blue-800 transition-all">
                        <input type="radio" name="status" value="izin" {{ old('status', $attendance->status) === 'izin' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        <span>Izin</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-purple-50 has-checked:border-purple-500 has-checked:bg-purple-50 has-checked:text-purple-800 transition-all">
                        <input type="radio" name="status" value="sakit" {{ old('status', $attendance->status) === 'sakit' ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                        <span>Sakit</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-rose-50 has-checked:border-rose-500 has-checked:bg-rose-50 has-checked:text-rose-800 transition-all">
                        <input type="radio" name="status" value="alpa" {{ old('status', $attendance->status) === 'alpa' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                        <span>Alpa</span>
                    </label>
                </div>
            </div>

            <!-- Jam Masuk & Jam Pulang -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Masuk (Check-In)</label>
                    <input type="time" name="check_in_time" 
                           value="{{ old('check_in_time', $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '') }}" 
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Pulang (Check-Out)</label>
                    <input type="time" name="check_out_time" 
                           value="{{ old('check_out_time', $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '') }}" 
                           class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
            </div>

            <!-- Interaktif Map Picker untuk Lokasi Presensi Siswa -->
            <div class="rounded-2xl border border-slate-200 p-4 sm:p-5 bg-slate-50/60 space-y-3.5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-800">
                            📍 Titik Lokasi Presensi di Maps
                        </label>
                        <p class="text-[11px] text-slate-500">Pilih titik presensi dari daftar preset atau klik/geser pin di peta.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($attendance->check_in_distance !== null)
                            <span id="map-distance-badge" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[11px] font-bold {{ $attendance->check_in_distance <= ($attendance->attendanceLocation?->radius_meters ?? 150) ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                {{ $attendance->check_in_distance }} meter
                            </span>
                        @endif
                        <span id="map-coords-badge" class="text-[11px] font-mono text-indigo-700 font-bold bg-white px-2.5 py-1 rounded-lg border border-slate-200">
                            {{ $attendance->check_in_lat ? number_format($attendance->check_in_lat, 6) . ', ' . number_format($attendance->check_in_lng, 6) : 'Belum Ada Koordinat' }}
                        </span>
                    </div>
                </div>

                <!-- Kontrol Presets & Quick Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                    <div class="sm:col-span-6">
                        <select name="attendance_location_id" id="select-location-preset" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none">
                            <option value="">-- Lokasi Kustom / Bebas di Peta --</option>
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}" 
                                        data-lat="{{ $loc->latitude }}" 
                                        data-lng="{{ $loc->longitude }}" 
                                        data-radius="{{ $loc->radius_meters }}"
                                        {{ old('attendance_location_id', $attendance->attendance_location_id) == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }} (Radius: {{ $loc->radius_meters }}m)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-6 flex items-center gap-2">
                        <button type="button" id="btn-set-banjar-rejo" class="flex-1 rounded-xl border border-indigo-200 bg-indigo-50 px-2.5 py-2 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors truncate">
                            Titik V8GJ+8W
                        </button>
                        <button type="button" id="btn-set-gps-current" class="flex-1 rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors truncate">
                            GPS Saya
                        </button>
                        <button type="button" id="btn-clear-coords" class="rounded-xl border border-rose-200 bg-rose-50 px-2.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-100 transition-colors" title="Kosongkan koordinat lokasi">
                            Hapus
                        </button>
                    </div>
                </div>

                <!-- Canvas Map Leaflet -->
                <div class="relative w-full h-64 rounded-xl overflow-hidden border border-slate-200 shadow-inner">
                    <div id="attendance-edit-map" class="w-full h-full"></div>
                </div>
                <p class="text-[11px] text-slate-500 flex items-center gap-1">
                    <svg class="h-3.5 w-3.5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <span>Klik pada peta atau geser pin merah untuk mengubah titik koordinat presensi siswa ini.</span>
                </p>

                <!-- Hidden Input Koordinat -->
                <input type="hidden" name="check_in_lat" id="edit-lat" value="{{ old('check_in_lat', $attendance->check_in_lat ?? ($primaryLocation?->latitude ?? -5.124188)) }}">
                <input type="hidden" name="check_in_lng" id="edit-lng" value="{{ old('check_in_lng', $attendance->check_in_lng ?? ($primaryLocation?->longitude ?? 105.332312)) }}">
            </div>

            <!-- Catatan / Keterangan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Keterangan</label>
                <textarea name="note" rows="3" placeholder="Tambahkan catatan jika diperlukan..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('note', $attendance->note) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('attendance-edit-map');
    if (!mapEl) return;

    const latInput = document.getElementById('edit-lat');
    const lngInput = document.getElementById('edit-lng');
    const coordsBadge = document.getElementById('map-coords-badge');
    const selectPreset = document.getElementById('select-location-preset');

    const defaultLat = parseFloat(latInput.value) || -5.124188;
    const defaultLng = parseFloat(lngInput.value) || 105.332312;

    const map = L.map('attendance-edit-map', {
        center: [defaultLat, defaultLng],
        zoom: 16,
        zoomControl: true,
        scrollWheelZoom: true
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const markerIcon = L.divIcon({
        className: 'custom-edit-pin',
        html: `<div style="background-color:#ef4444; width:30px; height:30px; border-radius:50% 50% 50% 0; transform:rotate(-45deg); border:3px solid #ffffff; box-shadow:0 4px 8px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center;">
                 <div style="width:8px; height:8px; background-color:white; border-radius:50%; transform:rotate(45deg);"></div>
               </div>`,
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -30]
    });

    let marker = L.marker([defaultLat, defaultLng], { draggable: true, icon: markerIcon }).addTo(map);
    let radiusCircle = null;

    function updateCoordinates(lat, lng) {
        latInput.value = lat ? lat.toFixed(7) : '';
        lngInput.value = lng ? lng.toFixed(7) : '';
        if (coordsBadge) {
            coordsBadge.textContent = lat && lng ? `${lat.toFixed(6)}, ${lng.toFixed(6)}` : 'Belum Ada Koordinat';
        }
    }

    function renderRadiusCircle(lat, lng, radius) {
        if (radiusCircle) {
            map.removeLayer(radiusCircle);
            radiusCircle = null;
        }
        if (radius && radius > 0) {
            radiusCircle = L.circle([lat, lng], {
                radius: radius,
                color: '#4f46e5',
                fillColor: '#6366f1',
                fillOpacity: 0.15,
                weight: 2,
                dashArray: '4, 4'
            }).addTo(map);
        }
    }

    // Check if initial preset selected
    if (selectPreset && selectPreset.selectedIndex > 0) {
        const opt = selectPreset.options[selectPreset.selectedIndex];
        if (opt && opt.dataset.radius) {
            renderRadiusCircle(parseFloat(opt.dataset.lat), parseFloat(opt.dataset.lng), parseInt(opt.dataset.radius));
        }
    }

    marker.on('drag', function(e) {
        const p = e.target.getLatLng();
        updateCoordinates(p.lat, p.lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Preset dropdown change
    if (selectPreset) {
        selectPreset.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.lat && opt.dataset.lng) {
                const targetLat = parseFloat(opt.dataset.lat);
                const targetLng = parseFloat(opt.dataset.lng);
                const targetRadius = parseInt(opt.dataset.radius) || 150;
                marker.setLatLng([targetLat, targetLng]);
                map.setView([targetLat, targetLng], 17);
                updateCoordinates(targetLat, targetLng);
                renderRadiusCircle(targetLat, targetLng, targetRadius);
            } else {
                if (radiusCircle) {
                    map.removeLayer(radiusCircle);
                    radiusCircle = null;
                }
            }
        });
    }

    // Preset button V8GJ+8W Banjar Rejo
    const btnBanjarRejo = document.getElementById('btn-set-banjar-rejo');
    if (btnBanjarRejo) {
        btnBanjarRejo.addEventListener('click', function() {
            const v8Lat = -5.124188;
            const v8Lng = 105.332312;
            marker.setLatLng([v8Lat, v8Lng]);
            map.setView([v8Lat, v8Lng], 17);
            updateCoordinates(v8Lat, v8Lng);
        });
    }

    // Preset button GPS Saya
    const btnGps = document.getElementById('btn-set-gps-current');
    if (btnGps) {
        btnGps.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('GPS tidak didukung oleh browser Anda.');
                return;
            }
            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const myLat = pos.coords.latitude;
                    const myLng = pos.coords.longitude;
                    marker.setLatLng([myLat, myLng]);
                    map.setView([myLat, myLng], 18);
                    updateCoordinates(myLat, myLng);
                },
                function(err) {
                    alert('Gagal membaca GPS: ' + err.message);
                },
                { enableHighAccuracy: true }
            );
        });
    }

    // Clear coordinates button
    const btnClear = document.getElementById('btn-clear-coords');
    if (btnClear) {
        btnClear.addEventListener('click', function() {
            latInput.value = '';
            lngInput.value = '';
            if (selectPreset) selectPreset.value = '';
            if (coordsBadge) coordsBadge.textContent = 'Belum Ada Koordinat';
            if (radiusCircle) {
                map.removeLayer(radiusCircle);
                radiusCircle = null;
            }
        });
    }

    setTimeout(function() {
        map.invalidateSize();
    }, 250);
});
</script>
@endpush
