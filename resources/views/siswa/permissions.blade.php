@extends('layouts.siswa', ['title' => 'Pengajuan Izin', 'header' => 'Pengajuan Izin Siswa'])

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Layanan Perizinan & Sakit</h2>
            <p class="text-xs text-slate-500">Ajukan surat izin atau keterangan sakit sebelum sesi pembelajaran dimulai.</p>
        </div>
    </div>

    <!-- Form Pengajuan Izin -->
    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs">
        <h3 class="text-base font-bold text-slate-800 mb-1">Formulir Pengajuan Izin Baru</h3>
        <p class="text-xs text-slate-500 mb-6">Pastikan mengisi alasan yang valid dan melampirkan berkas bukti jika ada.</p>

        <form action="{{ route('siswa.permissions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Izin / Sakit *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Izin *</label>
                    <select name="type" required class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                        <option value="izin" {{ old('type') == 'izin' ? 'selected' : '' }}>Izin Keperluan Mendesak</option>
                        <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>Sakit / Rawat Jalan</option>
                        <option value="lainnya" {{ old('type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Ketidakhadiran *</label>
                <textarea name="reason" rows="3" required placeholder="Tuliskan keterangan detail alasan Anda tidak dapat hadir..." class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">{{ old('reason') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Lampiran Berkas Bukti (Surat Dokter / Dokumen Pendukung)</label>
                <input type="file" name="evidence" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[10px] text-slate-400 mt-1">Format didukung: PDF, JPG, PNG. Maksimal 3 MB.</p>
            </div>

            <div class="pt-3 flex justify-end">
                <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition-colors">
                    Kirim Pengajuan Izin
                </button>
            </div>
        </form>
    </div>

    <!-- Riwayat Pengajuan Izin -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-bold text-slate-800">Riwayat Pengajuan Izin Saya</h3>
            <p class="text-xs text-slate-500">Status persetujuan oleh tim akademik.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal Izin</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Alasan</th>
                        <th class="px-6 py-4">Bukti Lampiran</th>
                        <th class="px-6 py-4">Status Persetujuan</th>
                        <th class="px-6 py-4">Catatan Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($permissions as $perm)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-700">
                                {{ $perm->date->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-md bg-slate-100 px-2 py-0.5 font-bold uppercase text-[10px] text-slate-700">
                                    {{ $perm->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 max-w-xs">
                                {{ $perm->reason }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($perm->evidence)
                                    <a href="{{ Storage::url($perm->evidence) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 hover:underline">
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($perm->status === 'disetujui')
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Disetujui</span>
                                @elseif ($perm->status === 'menunggu')
                                    <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">Menunggu</span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-500 italic">
                                {{ $perm->admin_note ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                Anda belum pernah mengajukan izin.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($permissions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $permissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
