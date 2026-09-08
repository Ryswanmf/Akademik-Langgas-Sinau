@extends('layouts.admin', ['title' => 'Data Siswa', 'header' => 'Kelola Data Siswa'])

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Siswa Akademi</h2>
            <p class="text-xs text-slate-500">Kelola informasi data pribadi, status akademik, dan kelas siswa.</p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Siswa Baru
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.students.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <!-- Search -->
            <div class="sm:col-span-2 relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama, email, nomor siswa, no HP..." 
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
            </div>

            <!-- Class Filter -->
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

            <!-- Status Filter & Button -->
            <div class="flex items-center gap-2">
                <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                </select>
                <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'class_id', 'status']))
                    <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Student Table -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">No. Siswa</th>
                        <th class="px-6 py-4">Kelas & Program</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Tanggal Masuk</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $student->photo_url }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-slate-200 shrink-0" alt="">
                                    <div>
                                        <a href="{{ route('admin.students.show', $student) }}" class="font-bold text-slate-800 hover:text-indigo-600">
                                            {{ $student->user->name ?? '-' }}
                                        </a>
                                        <p class="text-[11px] text-slate-400">{{ $student->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono font-medium text-slate-700">
                                {{ $student->student_number }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">{{ $student->class->name ?? 'Belum ada kelas' }}</p>
                                <p class="text-[11px] text-slate-500">{{ $student->program }}</p>
                                @if ($student->school_origin)
                                    <p class="text-[10px] text-emerald-700 font-medium truncate max-w-xs">{{ $student->school_origin }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $student->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $student->entry_date ? $student->entry_date->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold capitalize transition-colors
                                        {{ $student->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : '' }}
                                        {{ $student->status === 'nonaktif' ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : '' }}
                                        {{ $student->status === 'lulus' ? 'bg-blue-50 text-blue-700 hover:bg-blue-100' : '' }}">
                                        <span>{{ $student->status }}</span>
                                        <svg class="h-3 w-3 opacity-60" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>

                                    <!-- Status changer popup -->
                                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute left-0 mt-1 w-32 rounded-xl bg-white p-1 shadow-lg ring-1 ring-slate-900/5 z-20 border border-slate-100">
                                        <form action="{{ route('admin.students.update-status', $student) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="status" value="aktif" class="w-full text-left rounded-lg px-2.5 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-50">Aktif</button>
                                            <button type="submit" name="status" value="nonaktif" class="w-full text-left rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Nonaktif</button>
                                            <button type="submit" name="status" value="lulus" class="w-full text-left rounded-lg px-2.5 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-50">Lulus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.attendances.print-student', $student) }}" target="_blank" title="Cetak Lembar Absensi Siswa" class="rounded-lg p-1.5 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.students.show', $student) }}" title="Detail" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.students.edit', $student) }}" title="Edit" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 hover:text-amber-600 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <!-- Delete modal trigger with Alpine -->
                                    <div x-data="{ openDelete: false }">
                                        <button @click="openDelete = true" title="Hapus" class="rounded-lg p-1.5 text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>

                                        <!-- Delete Confirmation Modal -->
                                        <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="openDelete = false"></div>
                                            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-left border border-slate-100 z-10">
                                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-100 text-rose-600 mb-4">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-base font-bold text-slate-900">Hapus Siswa Ini?</h3>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    Apakah Anda yakin ingin menghapus data <strong>{{ $student->user->name }}</strong>? Seluruh data absensi, nilai, dan akun login akan dihapus secara permanen.
                                                </p>
                                                <div class="mt-6 flex justify-end gap-2.5">
                                                    <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm">Ya, Hapus</button>
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
                                Tidak ada data siswa ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
