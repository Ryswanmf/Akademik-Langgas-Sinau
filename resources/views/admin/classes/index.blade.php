@extends('layouts.admin', ['title' => 'Daftar Kelas', 'header' => 'Kelola Kelas Pelatihan'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Kelas Pelatihan</h2>
            <p class="text-xs text-slate-500">Kelola rombongan belajar, program keahlian, dan alokasi siswa.</p>
        </div>
        <a href="{{ route('admin.classes.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Kelas Baru
        </a>
    </div>

    <!-- Search Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.classes.index') }}" class="flex gap-2 max-w-md">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari nama kelas atau program..." 
                class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                Cari
            </button>
            @if (request('search'))
                <a href="{{ route('admin.classes.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Classes Grid / Table -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($classes as $class)
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider
                            {{ $class->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                            {{ $class->status }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">
                            {{ $class->program }}
                        </span>
                    </div>

                    <h3 class="mt-3 text-lg font-bold text-slate-800 line-clamp-1">
                        {{ $class->name }}
                    </h3>
                    <p class="mt-1 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $class->description ?? 'Tidak ada deskripsi tambahan.' }}
                    </p>

                    <div class="mt-5 grid grid-cols-2 gap-2 border-t border-slate-100 pt-4">
                        <div class="rounded-xl bg-slate-50 p-2.5 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Siswa Terdaftar</p>
                            <p class="text-lg font-black text-slate-800 mt-0.5">{{ $class->students_count }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-2.5 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Total Jadwal</p>
                            <p class="text-lg font-black text-slate-800 mt-0.5">{{ $class->schedules_count }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4">
                    <a href="{{ route('admin.classes.show', $class) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                        Lihat Siswa &rarr;
                    </a>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.classes.edit', $class) }}" title="Edit Kelas" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-amber-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                        </a>
                        <div x-data="{ openDelete: false }">
                            <button @click="openDelete = true" title="Hapus Kelas" class="rounded-lg p-1.5 text-slate-400 hover:bg-rose-50 hover:text-rose-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>

                            <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="openDelete = false"></div>
                                <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-left border border-slate-100 z-10">
                                    <h3 class="text-base font-bold text-slate-900">Hapus Kelas Ini?</h3>
                                    <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                        Apakah Anda yakin ingin menghapus kelas <strong>{{ $class->name }}</strong>? Siswa yang berada di kelas ini akan kehilangan asosiasi kelas.
                                    </p>
                                    <div class="mt-6 flex justify-end gap-2.5">
                                        <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center rounded-2xl border border-dashed border-slate-200 bg-white">
                <p class="text-xs text-slate-400">Belum ada data kelas yang terdaftar.</p>
            </div>
        @endforelse
    </div>

    @if ($classes->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $classes->links() }}
        </div>
    @endif
</div>
@endsection
