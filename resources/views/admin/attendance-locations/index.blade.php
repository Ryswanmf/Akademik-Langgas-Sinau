@extends('layouts.admin', ['title' => 'Titik GPS & Jam Absensi', 'header' => 'Pengaturan Titik GPS & Waktu Presensi'])

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-container {
        font-family: inherit;
        z-index: 10 !important;
    }
    .custom-popup .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        padding: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="{ 
    deleteModalOpen: false, 
    deleteAction: '', 
    deleteName: '',
    confirmDelete(action, name) {
        this.deleteAction = action;
        this.deleteName = name;
        this.deleteModalOpen = true;
    }
}">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Pengaturan Titik GPS & Jam Absensi</h1>
                <span class="rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-[10px] font-black uppercase px-2.5 py-0.5 tracking-wider">Geofencing</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kelola titik koordinat GPS presensi, radius geofencing kehadiran siswa, serta aturan waktu presensi masuk dan pulang.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.attendance-locations.create') }}" 
               class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-indigo-700 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Titik GPS
            </a>
        </div>
    </div>

    <!-- Stat Cards Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Titik -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Titik GPS</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalLocations }}</p>
            <p class="text-[11px] text-slate-500 mt-1">{{ $activeLocations }} titik aktif digunakan</p>
        </div>

        <!-- Card 2: Titik Utama Aktif -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Titik Acuan Utama</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-xs font-black text-slate-900 mt-2 truncate" title="{{ $primaryLocation?->name }}">
                {{ $primaryLocation?->name ?? 'Belum ada' }}
            </p>
            <p class="text-[11px] font-semibold text-indigo-600 mt-1 font-mono truncate">
                {{ $primaryLocation?->plus_code ?? ($primaryLocation ? $primaryLocation->latitude . ', ' . $primaryLocation->longitude : '-') }}
            </p>
        </div>

        <!-- Card 3: Radius Toleransi Aktif -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Radius Geofence</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-900 mt-2">{{ $primaryLocation?->radius_meters ?? 150 }} <span class="text-sm font-semibold text-slate-500">Meter</span></p>
            <p class="text-[11px] text-slate-500 mt-1">
                Kunci Radius: <span class="font-bold {{ $primaryLocation?->strict_radius ? 'text-emerald-600' : 'text-amber-600' }}">{{ $primaryLocation?->strict_radius ? 'Ketat (Wajib)' : 'Toleran' }}</span>
            </p>
        </div>

        <!-- Card 4: Jadwal Jam Operasional -->
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jadwal Masuk & Pulang</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-sm font-black text-slate-900 mt-2">
                {{ $primaryLocation?->in_start ?? '08:00' }} - {{ $primaryLocation?->in_on_time_end ?? '09:30' }} WIB
            </p>
            <p class="text-[11px] text-slate-500 mt-1">
                Pulang: {{ $primaryLocation?->out_start ?? '14:00' }} - {{ $primaryLocation?->out_end ?? '17:00' }} WIB
            </p>
        </div>
    </div>

    <!-- Peta Interaktif Sebaran Titik GPS Aktif -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-2xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-bold text-slate-900">Peta Sebaran Titik Presensi & Geofence</h3>
                    <span class="rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase px-2 py-0.5">Live Map</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Lingkaran biru muda menandai zona radius presensi siswa. 
                    <span class="text-indigo-600 font-semibold">💡 Klik di mana saja pada peta</span> untuk menambahkan titik lokasi baru langsung di koordinat tersebut.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" id="btn-focus-primary" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                    <svg class="h-3.5 w-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Pusatkan ke Titik Utama
                </button>
            </div>
        </div>

        <!-- Canvas Peta Leaflet -->
        <div class="relative w-full h-80 sm:h-96 rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
            <div id="overview-map" class="w-full h-full"></div>
        </div>
    </div>

    <!-- Data Table Titik Lokasi -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-2xs overflow-hidden">
        <!-- Filter & Search Toolbar -->
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form method="GET" action="{{ route('admin.attendance-locations.index') }}" class="flex flex-wrap items-center gap-2.5 flex-1">
                <div class="relative flex-1 min-w-[200px] max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lokasi, alamat, atau Plus Code..." 
                           class="w-full rounded-xl border border-slate-200/80 bg-slate-50/50 pl-9 pr-3.5 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-indigo-600 focus:outline-none focus:ring-1 focus:ring-indigo-600 transition-all">
                </div>

                <select name="status" onchange="this.form.submit()" 
                        class="rounded-xl border border-slate-200/80 bg-slate-50/50 px-3 py-2 text-xs text-slate-700 focus:bg-white focus:border-indigo-600 focus:outline-none transition-all">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
                </select>

                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.attendance-locations.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                        Reset Filter
                    </a>
                @endif
            </form>

            <span class="text-xs text-slate-400 font-medium whitespace-nowrap">
                Menampilkan {{ $locations->total() }} titik lokasi
            </span>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="px-5 py-3.5">Nama & Lokasi</th>
                        <th class="px-4 py-3.5">Koordinat GPS</th>
                        <th class="px-4 py-3.5">Radius Geofence</th>
                        <th class="px-4 py-3.5">Aturan Jam Masuk</th>
                        <th class="px-4 py-3.5">Aturan Jam Pulang</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($locations as $loc)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $loc->is_active ? '' : 'opacity-60 bg-slate-50/30' }}">
                            <!-- Nama & Lokasi -->
                            <td class="px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $loc->is_active ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-400' }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 max-w-xs">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-bold text-slate-900 truncate">{{ $loc->name }}</p>
                                            @if($loop->first && $loc->is_active)
                                                <span class="rounded bg-indigo-100 text-indigo-700 text-[9px] font-black uppercase px-1.5 py-0.5">Utama</span>
                                            @endif
                                        </div>
                                        @if($loc->plus_code)
                                            <p class="text-[11px] font-mono text-indigo-600 mt-0.5 font-semibold flex items-center gap-1">
                                                <svg class="h-3 w-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                                {{ $loc->plus_code }}
                                            </p>
                                        @endif
                                        <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $loc->address }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Koordinat GPS -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-mono text-[11px] space-y-0.5">
                                    <p class="text-slate-900 font-semibold">Lat: {{ number_format($loc->latitude, 6) }}</p>
                                    <p class="text-slate-600">Lng: {{ number_format($loc->longitude, 6) }}</p>
                                    <a href="{{ $loc->google_maps_url }}" target="_blank" rel="noopener noreferrer" 
                                       class="inline-flex items-center gap-1 text-[10px] text-indigo-600 hover:text-indigo-800 hover:underline pt-0.5">
                                        Buka Google Maps
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>
                                </div>
                            </td>

                            <!-- Radius Geofence -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-900">
                                    {{ $loc->radius_meters }} Meter
                                </span>
                                <div class="mt-1">
                                    @if($loc->strict_radius)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-200">
                                            Wajib Dalam Radius
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200">
                                            Toleran (Peringatan)
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Jam Masuk -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="space-y-0.5 text-[11px]">
                                    <p class="font-bold text-emerald-700">Tepat: {{ $loc->in_start }} - {{ $loc->in_on_time_end }}</p>
                                    <p class="text-amber-600 font-medium">Terlambat: s/d {{ $loc->in_late_end }}</p>
                                    <p class="text-[10px] text-slate-400">Lewat: Ditutup</p>
                                </div>
                            </td>

                            <!-- Jam Pulang -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="space-y-0.5 text-[11px]">
                                    <p class="font-bold text-blue-700">{{ $loc->out_start }} - {{ $loc->out_end }}</p>
                                    <p class="text-[10px] text-slate-400">Auto-Alpa: {{ $loc->auto_alpa_time }} WIB</p>
                                </div>
                            </td>

                            <!-- Status Aktif / Nonaktif -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <form method="POST" action="{{ route('admin.attendance-locations.toggle-active', $loc) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider transition-all {{ $loc->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}"
                                            title="Klik untuk mengubah status aktif/nonaktif">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $loc->is_active ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                                        {{ $loc->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.attendance-locations.edit', $loc) }}" 
                                       class="rounded-lg p-1.5 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors"
                                       title="Edit Titik GPS & Jam Absensi">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>

                                    <button type="button" 
                                            @click="confirmDelete('{{ route('admin.attendance-locations.destroy', $loc) }}', '{{ addslashes($loc->name) }}')"
                                            class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors"
                                            title="Hapus Titik GPS">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    <p class="font-bold text-slate-600">Tidak ada data titik lokasi GPS</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Silakan tambahkan titik lokasi presensi pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($locations->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $locations->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div x-show="deleteModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" 
         x-cloak>
        <div @click.away="deleteModalOpen = false" 
             class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl space-y-4">
            <div class="flex items-center gap-3 text-rose-600">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-rose-50">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Lokasi</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus titik GPS <span class="font-bold text-slate-900" x-text="deleteName"></span>? Siswa tidak akan dapat melakukan presensi di titik ini lagi.
            </p>

            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" @click="deleteModalOpen = false" 
                        class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <form :action="deleteAction" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-rose-700 transition-colors">
                        Ya, Hapus Titik
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationsData = @json($mapLocations);

    if (!document.getElementById('overview-map')) return;

    // Default center ke lokasi pertama atau koordinat Banjar Rejo Lampung Timur
    const defaultLat = locationsData.length > 0 ? locationsData[0].lat : -5.124188;
    const defaultLng = locationsData.length > 0 ? locationsData[0].lng : 105.332312;

    const map = L.map('overview-map', {
        center: [defaultLat, defaultLng],
        zoom: 16,
        zoomControl: true,
        scrollWheelZoom: false
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    const markersGroup = L.featureGroup();

    // Custom marker icon
    const customIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color:#4f46e5; width:28px; height:28px; border-radius:50%; border:3px solid #ffffff; box-shadow:0 4px 6px -1px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; color:white;">
                 <svg style="width:14px; height:14px;" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                 </svg>
               </div>`,
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -14]
    });

    locationsData.forEach(loc => {
        // Marker
        const marker = L.marker([loc.lat, loc.lng], { icon: customIcon }).addTo(map);
        
        // Circle Radius Geofence
        const circle = L.circle([loc.lat, loc.lng], {
            radius: loc.radius,
            color: '#4f46e5',
            fillColor: '#6366f1',
            fillOpacity: 0.18,
            weight: 2,
            dashArray: '4, 4'
        }).addTo(map);

        const popupContent = `
            <div class="p-2 space-y-1.5 text-slate-800">
                <div class="flex items-center gap-1.5">
                    <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10b981;"></span>
                    <strong style="font-size:13px; font-weight:800;">${loc.name}</strong>
                </div>
                ${loc.plus_code ? `<p style="font-size:11px; font-family:monospace; color:#4f46e5; margin:0; font-weight:700;">📍 ${loc.plus_code}</p>` : ''}
                <p style="font-size:11px; color:#64748b; margin:0;">${loc.address}</p>
                <div style="font-size:11px; background:#f8fafc; padding:6px; border-radius:8px; margin-top:6px; border:1px solid #e2e8f0;">
                    <div><strong>Radius:</strong> ${loc.radius} meter</div>
                    <div><strong>Jam Masuk:</strong> ${loc.in_start} - ${loc.in_on_time_end} WIB</div>
                    <div><strong>Jam Pulang:</strong> ${loc.out_start} - ${loc.out_end} WIB</div>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, { className: 'custom-popup' });
        markersGroup.addLayer(marker);
        markersGroup.addLayer(circle);
    });

    if (locationsData.length > 1) {
        map.fitBounds(markersGroup.getBounds().pad(0.2));
    }

    // Klik di mana saja pada peta untuk menampilkan opsi buat titik lokasi baru di koordinat tersebut
    const createBaseUrl = "{{ route('admin.attendance-locations.create') }}";
    const clickPopup = L.popup();
    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        const url = `${createBaseUrl}?lat=${lat}&lng=${lng}`;
        clickPopup
            .setLatLng(e.latlng)
            .setContent(`
                <div style="font-family: inherit; font-size: 12px; padding: 4px; min-width: 180px;">
                    <div style="font-weight: 700; color: #1e293b; margin-bottom: 2px;">📍 Koordinat Pilihan</div>
                    <div style="font-family: monospace; font-size: 11px; color: #4338ca; font-weight: 600; margin-bottom: 8px; background: #e0e7ff; padding: 3px 6px; border-radius: 4px;">
                        ${lat}, ${lng}
                    </div>
                    <a href="${url}" style="display: block; text-align: center; background: #4f46e5; color: #ffffff; padding: 6px 10px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 11px; box-shadow: 0 2px 4px rgba(79,70,229,0.3);">
                        + Buat Titik GPS di Sini
                    </a>
                </div>
            `)
            .openOn(map);
    });

    // Button focus ke titik utama
    const btnFocus = document.getElementById('btn-focus-primary');
    if (btnFocus) {
        btnFocus.addEventListener('click', function() {
            map.flyTo([defaultLat, defaultLng], 17, { duration: 1.2 });
        });
    }
});
</script>
@endpush
