@extends('layouts.admin', ['title' => 'Nilai Siswa', 'header' => 'Kelola Nilai Akademik'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Nilai Siswa</h2>
            <p class="text-xs text-slate-500">Penilaian modul pembelajaran dan evaluasi kompetensi siswa.</p>
        </div>
        <a href="{{ route('admin.grades.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Input Nilai Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.grades.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <div class="sm:col-span-2">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari materi / subjek..." 
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
                <select name="student_id" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">Semua Siswa</option>
                    @foreach ($students as $s)
                        <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->user->name ?? $s->student_number }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'class_id', 'student_id']))
                    <a href="{{ route('admin.grades.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Grades Table -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Materi / Subjek</th>
                        <th class="px-6 py-4 text-center">Nilai</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($grades as $grade)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-2.5">
                                <img src="{{ $grade->student->photo_url }}" class="h-8 w-8 rounded-xl object-cover ring-1 ring-slate-200 shrink-0" alt="">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $grade->student->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $grade->student->student_number }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $grade->class->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $grade->subject }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center rounded-xl font-black text-sm px-3 py-1
                                    {{ $grade->score >= 85 ? 'bg-emerald-50 text-emerald-700' : ($grade->score >= 70 ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ $grade->score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-xs truncate">
                                {{ $grade->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.grades.edit', $grade) }}" title="Edit" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-amber-600">
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
                                                <h3 class="text-base font-bold text-slate-900">Hapus Nilai?</h3>
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    Hapus nilai <strong>{{ $grade->subject }}</strong> untuk {{ $grade->student->user->name }}?
                                                </p>
                                                <div class="mt-6 flex justify-end gap-2.5">
                                                    <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                                    <form action="{{ route('admin.grades.destroy', $grade) }}" method="POST">
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
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data nilai ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($grades->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $grades->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
