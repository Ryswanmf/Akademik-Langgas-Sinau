@extends('layouts.admin', ['title' => 'Buat Pengumuman', 'header' => 'Buat Pengumuman Baru'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Formulir Pengumuman</h2>
            <p class="text-xs text-slate-500">Publikasikan kabar dan agenda resmi untuk seluruh siswa.</p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Judul Pengumuman *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="cth. Jadwal Ujian Akhir Semester Genap" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Publikasi *</label>
                    <select name="status" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published (Langsung Tampil)</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan Konsep)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Publikasi</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', date('Y-m-d\TH:i')) }}" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Isi Pengumuman *</label>
                <textarea name="content" rows="6" required placeholder="Tuliskan isi pengumuman lengkap..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Gambar Banner / Poster (Opsional)</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maks 2 MB.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.announcements.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700">
                    Simpan & Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
