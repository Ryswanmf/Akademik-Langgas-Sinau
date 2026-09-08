@extends('layouts.admin', ['title' => 'Edit Nilai', 'header' => 'Edit Nilai Siswa'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Nilai: {{ $grade->subject }}</h2>
            <p class="text-xs text-slate-500">Perbarui nilai dan evaluasi untuk {{ $grade->student->user->name }}.</p>
        </div>
        <a href="{{ route('admin.grades.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.grades.update', $grade) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Siswa *</label>
                <select name="student_id" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    @foreach ($students as $s)
                        <option value="{{ $s->id }}" {{ old('student_id', $grade->student_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->user->name }} ({{ $s->student_number }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Kelas Pelatihan</label>
                <input 
                    type="text" 
                    name="class_name" 
                    value="{{ old('class_name', $grade->class->name ?? '') }}" 
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

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Materi / Mata Pelajaran *</label>
                    <input type="text" name="subject" value="{{ old('subject', $grade->subject) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nilai (0 - 100) *</label>
                    <input type="number" step="0.01" min="0" max="100" name="score" value="{{ old('score', $grade->score) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan / Feedback Evaluasi</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('description', $grade->description) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.grades.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
