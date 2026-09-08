@extends('layouts.admin', ['title' => 'Input Absensi', 'header' => 'Input Presensi Siswa'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    mode: 'massal',
    setAllStatus(status) {
        document.querySelectorAll('input[type=radio][value=' + status + ']').forEach(el => {
            el.checked = true;
        });
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Catat Presensi Kehadiran</h2>
            <p class="text-xs text-slate-500">Pilih mode penginputan absensi per kelas atau perorangan.</p>
        </div>
        <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <!-- Mode Selector Tabs -->
    <div class="flex rounded-2xl bg-slate-200/70 p-1 max-w-sm">
        <button type="button" @click="mode = 'massal'" :class="mode === 'massal' ? 'bg-white text-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="flex-1 py-2 text-xs rounded-xl transition-all">
            Presensi Per Kelas (Massal)
        </button>
        <button type="button" @click="mode = 'tunggal'" :class="mode === 'tunggal' ? 'bg-white text-indigo-600 font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="flex-1 py-2 text-xs rounded-xl transition-all">
            Presensi Perorangan
        </button>
    </div>

    <!-- Mode 1: Presensi Massal Per Kelas -->
    <div x-show="mode === 'massal'" class="space-y-6">
        <!-- Step 1: Select Class & Schedule -->
        <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs">
            <h3 class="text-sm font-bold text-slate-800 mb-4">1. Pilih Kelas & Jadwal</h3>
            <form method="GET" action="{{ route('admin.attendances.create') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kelas *</label>
                    <select name="class_id" onchange="this.form.submit()" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->students->count() }} Siswa)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Sesi / Jadwal Terkait (Opsional)</label>
                    <select name="schedule_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Tanpa Jadwal Tertentu --</option>
                        @foreach ($schedules as $sched)
                            @if (!$selectedClassId || $sched->class_id == $selectedClassId)
                                <option value="{{ $sched->id }}" {{ $selectedScheduleId == $sched->id ? 'selected' : '' }}>
                                    {{ $sched->date->format('d/m/Y') }} - {{ $sched->title }} ({{ $sched->class->name ?? '-' }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        @if ($selectedClassId && $studentsInClass->count() > 0)
            <!-- Step 2: Mark Attendance Table -->
            <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
                <form action="{{ route('admin.attendances.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $selectedScheduleId }}">

                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">2. Lembar Presensi Siswa</h3>
                            <p class="text-xs text-slate-500">Tandai status kehadiran setiap siswa di bawah ini.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="rounded-xl border border-slate-200 px-3 py-1.5 text-xs focus:border-indigo-500 focus:ring-2">
                            </div>
                            <!-- Quick action: Set Semua Hadir -->
                            <button type="button" @click="setAllStatus('hadir')" class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700 hover:bg-emerald-100 transition-colors">
                                ✓ Set Semua Hadir
                            </button>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($studentsInClass as $std)
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50">
                                <div class="flex items-center gap-3 min-w-[200px]">
                                    <img src="{{ $std->photo_url }}" class="h-9 w-9 rounded-xl object-cover ring-1 ring-slate-200" alt="">
                                    <div>
                                        <p class="font-bold text-xs text-slate-800">{{ $std->user->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $std->student_number }}</p>
                                    </div>
                                </div>

                                <!-- Radio status options -->
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs font-semibold">
                                    <label class="flex items-center gap-1.5 cursor-pointer rounded-xl border border-slate-200 px-3 py-1.5 hover:bg-emerald-50 has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:text-emerald-800">
                                        <input type="radio" name="students_attendance[{{ $std->id }}][status]" value="hadir" checked class="text-emerald-600 focus:ring-emerald-500">
                                        <span>Hadir</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer rounded-xl border border-slate-200 px-3 py-1.5 hover:bg-amber-50 has-checked:border-amber-500 has-checked:bg-amber-50 has-checked:text-amber-800">
                                        <input type="radio" name="students_attendance[{{ $std->id }}][status]" value="terlambat" class="text-amber-600 focus:ring-amber-500">
                                        <span>Terlambat</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer rounded-xl border border-slate-200 px-3 py-1.5 hover:bg-blue-50 has-checked:border-blue-500 has-checked:bg-blue-50 has-checked:text-blue-800">
                                        <input type="radio" name="students_attendance[{{ $std->id }}][status]" value="izin" class="text-blue-600 focus:ring-blue-500">
                                        <span>Izin</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer rounded-xl border border-slate-200 px-3 py-1.5 hover:bg-purple-50 has-checked:border-purple-500 has-checked:bg-purple-50 has-checked:text-purple-800">
                                        <input type="radio" name="students_attendance[{{ $std->id }}][status]" value="sakit" class="text-purple-600 focus:ring-purple-500">
                                        <span>Sakit</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 cursor-pointer rounded-xl border border-slate-200 px-3 py-1.5 hover:bg-rose-50 has-checked:border-rose-500 has-checked:bg-rose-50 has-checked:text-rose-800">
                                        <input type="radio" name="students_attendance[{{ $std->id }}][status]" value="alpa" class="text-rose-600 focus:ring-rose-500">
                                        <span>Alpa</span>
                                    </label>
                                </div>

                                <!-- Note input -->
                                <div class="w-full sm:w-48">
                                    <input type="text" name="students_attendance[{{ $std->id }}][note]" placeholder="Catatan opsional..." class="w-full rounded-xl border border-slate-200 px-3 py-1.5 text-xs focus:border-indigo-500">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-3">
                        <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700">
                            Simpan Presensi Kelas
                        </button>
                    </div>
                </form>
            </div>
        @elseif ($selectedClassId)
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white p-8 text-center text-slate-400 text-xs">
                Tidak ada siswa aktif dalam kelas yang dipilih.
            </div>
        @endif
    </div>

    <!-- Mode 2: Presensi Tunggal Perorangan -->
    <div x-show="mode === 'tunggal'" x-cloak class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <h3 class="text-sm font-bold text-slate-800 mb-4">Input Presensi Tunggal</h3>
        <form action="{{ route('admin.attendances.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Siswa *</label>
                <select name="student_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach ($allStudents as $s)
                        <option value="{{ $s->id }}">
                            {{ $s->user->name }} - {{ $s->student_number }} {{ $s->program ? '('.$s->program.')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal *</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jadwal Sesi Terkait (Opsional)</label>
                    <select name="schedule_id" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">-- Tanpa Jadwal Tertentu --</option>
                        @foreach ($schedules as $sched)
                            <option value="{{ $sched->id }}">{{ $sched->date->format('d/m/Y') }} - {{ $sched->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status Kehadiran *</label>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-xs font-semibold">
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-emerald-50 has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:text-emerald-800">
                        <input type="radio" name="status" value="hadir" checked class="text-emerald-600 focus:ring-emerald-500">
                        <span>Hadir</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-amber-50 has-checked:border-amber-500 has-checked:bg-amber-50 has-checked:text-amber-800">
                        <input type="radio" name="status" value="terlambat" class="text-amber-600 focus:ring-amber-500">
                        <span>Terlambat</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-blue-50 has-checked:border-blue-500 has-checked:bg-blue-50 has-checked:text-blue-800">
                        <input type="radio" name="status" value="izin" class="text-blue-600 focus:ring-blue-500">
                        <span>Izin</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-purple-50 has-checked:border-purple-500 has-checked:bg-purple-50 has-checked:text-purple-800">
                        <input type="radio" name="status" value="sakit" class="text-purple-600 focus:ring-purple-500">
                        <span>Sakit</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-rose-50 has-checked:border-rose-500 has-checked:bg-rose-50 has-checked:text-rose-800">
                        <input type="radio" name="status" value="alpa" class="text-rose-600 focus:ring-rose-500">
                        <span>Alpa</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Keterangan</label>
                <textarea name="note" rows="2" placeholder="Alasan izin/keterangan lain..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"></textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700">
                    Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
