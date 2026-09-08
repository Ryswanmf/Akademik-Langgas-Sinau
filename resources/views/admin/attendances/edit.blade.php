@extends('layouts.admin', ['title' => 'Ubah Absensi', 'header' => 'Ubah Data Presensi'])

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Ubah Status Absensi</h2>
            <p class="text-xs text-slate-500">Koreksi status presensi siswa.</p>
        </div>
        <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 mb-6 flex items-center gap-3">
            <img src="{{ $attendance->student->photo_url }}" class="h-10 w-10 rounded-xl object-cover" alt="">
            <div>
                <h3 class="text-xs font-bold text-slate-800">{{ $attendance->student->user->name }}</h3>
                <p class="text-[11px] text-slate-500">{{ $attendance->student->program ?: ($attendance->student->school_origin ?: 'Siswa LKP') }} • Tanggal: {{ $attendance->date->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <form action="{{ route('admin.attendances.update', $attendance) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Status Kehadiran *</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs font-semibold">
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-emerald-50 has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:text-emerald-800">
                        <input type="radio" name="status" value="hadir" {{ $attendance->status === 'hadir' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <span>Hadir</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-amber-50 has-checked:border-amber-500 has-checked:bg-amber-50 has-checked:text-amber-800">
                        <input type="radio" name="status" value="terlambat" {{ $attendance->status === 'terlambat' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                        <span>Terlambat</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-blue-50 has-checked:border-blue-500 has-checked:bg-blue-50 has-checked:text-blue-800">
                        <input type="radio" name="status" value="izin" {{ $attendance->status === 'izin' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        <span>Izin</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-purple-50 has-checked:border-purple-500 has-checked:bg-purple-50 has-checked:text-purple-800">
                        <input type="radio" name="status" value="sakit" {{ $attendance->status === 'sakit' ? 'checked' : '' }} class="text-purple-600 focus:ring-purple-500">
                        <span>Sakit</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer rounded-xl border border-slate-200 p-3 hover:bg-rose-50 has-checked:border-rose-500 has-checked:bg-rose-50 has-checked:text-rose-800">
                        <input type="radio" name="status" value="alpa" {{ $attendance->status === 'alpa' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                        <span>Alpa</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Keterangan</label>
                <textarea name="note" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('note', $attendance->note) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.attendances.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
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
