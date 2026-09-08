@extends('layouts.admin', ['title' => 'Detail Pengajuan Izin', 'header' => 'Tinjau Pengajuan Izin'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.permissions.index') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali ke Daftar Izin
        </a>
        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold capitalize
            {{ $permission->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : '' }}
            {{ $permission->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : '' }}
            {{ $permission->status === 'ditolak' ? 'bg-rose-50 text-rose-700' : '' }}">
            Status: {{ $permission->status }}
        </span>
    </div>

    <!-- Permission Details Card -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs space-y-6">
        <!-- Student Info Header -->
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
            <img src="{{ $permission->student->photo_url }}" class="h-14 w-14 rounded-2xl object-cover ring-2 ring-slate-100" alt="">
            <div>
                <h3 class="text-lg font-bold text-slate-900">{{ $permission->student->user->name }}</h3>
                <p class="text-xs text-slate-500 font-mono">{{ $permission->student->student_number }} • Kelas: {{ $permission->student->class->name ?? 'Belum ada kelas' }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Program: {{ $permission->student->program }}</p>
            </div>
        </div>

        <!-- Request Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                <span class="text-[10px] font-bold uppercase text-slate-400">Tanggal Izin</span>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $permission->date->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                <span class="text-[10px] font-bold uppercase text-slate-400">Jenis Perizinan</span>
                <p class="text-sm font-bold text-indigo-700 mt-1 uppercase">{{ $permission->type }}</p>
            </div>
        </div>

        <!-- Reason -->
        <div>
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alasan Ketidakhadiran</h4>
            <div class="rounded-2xl bg-slate-50/70 border border-slate-100 p-4 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                {{ $permission->reason }}
            </div>
        </div>

        <!-- Evidence Attachment -->
        <div>
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Berkas / Surat Bukti</h4>
            @if ($permission->evidence)
                <div class="flex items-center justify-between rounded-2xl border border-indigo-100 bg-indigo-50/60 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-indigo-900">Lampiran Bukti Izin</p>
                            <p class="text-[10px] text-indigo-600">Surat keterangan / dokter terlampir</p>
                        </div>
                    </div>
                    <a href="{{ Storage::url($permission->evidence) }}" target="_blank" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition-colors">
                        Buka Dokumen Bukti
                    </a>
                </div>
            @else
                <p class="text-xs text-slate-400 italic">Tidak ada lampiran dokumen bukti.</p>
            @endif
        </div>

        <!-- Approval / Rejection Form -->
        <div class="pt-6 border-t border-slate-100">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Tindakan Admin</h4>
            <form action="{{ route('admin.permissions.update-status', $permission) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Admin (Opsional)</label>
                    <textarea name="admin_note" rows="3" placeholder="Masukkan catatan atau instruksi tindak lanjut untuk siswa..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('admin_note', $permission->admin_note) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" name="status" value="disetujui" class="flex-1 rounded-xl bg-emerald-600 py-2.5 px-4 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors flex items-center justify-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Setujui Izin
                    </button>
                    <button type="submit" name="status" value="ditolak" class="flex-1 rounded-xl bg-rose-600 py-2.5 px-4 text-xs font-bold text-white shadow-md shadow-rose-600/20 hover:bg-rose-700 transition-colors flex items-center justify-center gap-1.5">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
