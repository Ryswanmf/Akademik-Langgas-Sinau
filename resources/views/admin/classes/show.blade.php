@extends('layouts.admin', ['title' => 'Detail Kelas - ' . $class->name, 'header' => 'Detail Kelas Pelatihan'])

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.classes.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 flex items-center gap-1.5">
            &larr; Kembali ke Daftar Kelas
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.classes.edit', $class) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-700">
                Edit Kelas
            </a>
        </div>
    </div>

    <!-- Class Detail Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider mb-2
                    {{ $class->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                    {{ $class->status }}
                </span>
                <h2 class="text-2xl font-black text-slate-800">{{ $class->name }}</h2>
                <p class="text-xs text-indigo-600 font-semibold mt-0.5">Program: {{ $class->program }}</p>
                <p class="text-xs text-slate-500 mt-2 max-w-2xl leading-relaxed">{{ $class->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            <div class="flex gap-3 shrink-0">
                <div class="rounded-2xl bg-indigo-50/70 border border-indigo-100 p-4 text-center min-w-[110px]">
                    <p class="text-[10px] font-bold text-indigo-500 uppercase">Siswa Terdaftar</p>
                    <p class="text-2xl font-black text-indigo-900 mt-1">{{ $class->students->count() }}</p>
                </div>
                <div class="rounded-2xl bg-purple-50/70 border border-purple-100 p-4 text-center min-w-[110px]">
                    <p class="text-[10px] font-bold text-purple-500 uppercase">Total Sesi Jadwal</p>
                    <p class="text-2xl font-black text-purple-900 mt-1">{{ $class->schedules->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students Table -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">Daftar Siswa di Kelas Ini</h3>
                <p class="text-xs text-slate-500">Siswa yang terdaftar dalam rombongan belajar kelas ini.</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                + Tambah Siswa
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4">Tanggal Masuk</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($class->students as $student)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="{{ $student->photo_url }}" class="h-8 w-8 rounded-xl object-cover ring-1 ring-slate-200 shrink-0" alt="">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $student->user->name ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $student->user->email ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-700 font-medium">
                                {{ $student->student_number }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $student->phone ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $student->entry_date ? $student->entry_date->translatedFormat('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold capitalize
                                    {{ $student->status === 'aktif' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $student->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.students.show', $student) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                    Lihat Detail &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada siswa yang dialokasikan ke kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">Jadwal Sesi Pelatihan</h3>
                <p class="text-xs text-slate-500">Agenda pembelajaran untuk kelas ini.</p>
            </div>
            <a href="{{ route('admin.schedules.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                + Tambah Jadwal
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Materi / Sesi</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Jam</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($class->schedules as $sched)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $sched->title }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $sched->date->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-mono">
                                {{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $sched->room }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $sched->description ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                Belum ada jadwal yang dijadwalkan untuk kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
