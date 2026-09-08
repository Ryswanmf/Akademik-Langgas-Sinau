@extends('layouts.admin', ['title' => 'Edit Kelas', 'header' => 'Edit Kelas Pelatihan'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Kelas: {{ $class->name }}</h2>
            <p class="text-xs text-slate-500">Perbarui informasi nama kelas, program, dan statusnya.</p>
        </div>
        <a href="{{ route('admin.classes.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.classes.update', $class) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kelas *</label>
                <input type="text" name="name" value="{{ old('name', $class->name) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Program Keahlian *</label>
                <input type="text" name="program" value="{{ old('program', $class->program) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Kelas</label>
                <textarea name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('description', $class->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status Kelas *</label>
                <select name="status" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    <option value="aktif" {{ old('status', $class->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $class->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.classes.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
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
