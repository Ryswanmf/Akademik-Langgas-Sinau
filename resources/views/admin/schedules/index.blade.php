@extends('layouts.admin', ['title' => 'Jadwal Pelatihan', 'header' => 'Kelola Jadwal Pelatihan'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Jadwal Pembelajaran</h2>
            <p class="text-xs text-slate-500">Kelola tanggal pertemuan, jam pelaksanaan, materi, dan ruangan kelas.</p>
        </div>
        <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Jadwal Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <div class="sm:col-span-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari materi atau ruangan..." 
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
            </div>
            <div>
                <select name="class_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}" 
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
                <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'class_id', 'date']))
                    <a href="{{ route('admin.schedules.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Schedules Table -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Materi / Sesi</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Hari & Tanggal</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($schedules as $sched)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $sched->title }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-indigo-600">
                                {{ $sched->class->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ $sched->date->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600">
                                {{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }} WIB
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                    {{ $sched->room }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate">
                                {{ $sched->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.schedules.edit', $sched) }}" title="Edit" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-amber-600">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>

                                    <div x-data="{ openDelete: false }">
                                        <button @click="openDelete = true" title="Hapus" class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>

                                        <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="openDelete = false"></div>
                                            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-left border border-slate-100 z-10">
                                                <h3 class="text-base font-bold text-slate-900">Hapus Jadwal?</h3>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    Hapus jadwal <strong>{{ $sched->title }}</strong>? Data absensi terkait jadwal ini juga akan terpengaruh.
                                                </p>
                                                <div class="mt-6 flex justify-end gap-2.5">
                                                    <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                                    <form action="{{ route('admin.schedules.destroy', $sched) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm">Hapus</button>
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
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada jadwal yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($schedules->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
