@extends('layouts.admin', ['title' => 'Edit Jadwal', 'header' => 'Edit Jadwal Pelatihan'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Jadwal: {{ $schedule->title }}</h2>
            <p class="text-xs text-slate-500">Perbarui waktu, ruangan, atau materi sesi pembelajaran.</p>
        </div>
        <a href="{{ route('admin.schedules.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kelas Pelatihan</label>
                <input 
                    type="text" 
                    name="class_name" 
                    value="{{ old('class_name', $schedule->class->name ?? '') }}" 
                    list="classList"
                    placeholder="cth. Desain Grafis A / Batch 1" 
                    class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                >
                <datalist id="classList">
                    @foreach ($classes as $c)
                        <option value="{{ $c->name }}">
                    @endforeach
                </datalist>
                <span class="text-[10px] text-slate-400 mt-1 block">Isi nama kelas pelatihan secara manual</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Materi / Judul Sesi *</label>
                <input type="text" name="title" value="{{ old('title', $schedule->title) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal *</label>
                    <input type="date" name="date" value="{{ old('date', optional($schedule->date)->format('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Mulai *</label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr($schedule->start_time, 0, 5)) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jam Selesai *</label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr($schedule->end_time, 0, 5)) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Ruangan / Tempat *</label>
                <input type="text" name="room" value="{{ old('room', $schedule->room) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Instruksi</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('description', $schedule->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.schedules.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
