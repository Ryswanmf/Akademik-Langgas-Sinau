@extends('layouts.admin', ['title' => 'Pengajuan Izin', 'header' => 'Kelola Pengajuan Izin Siswa'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Pengajuan Izin & Sakit</h2>
            <p class="text-xs text-slate-500">Tinjau permohonan izin dari siswa dan tentukan persetujuan.</p>
        </div>
    </div>

    <!-- Status Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <a href="{{ route('admin.permissions.index', ['status' => 'menunggu']) }}" class="rounded-2xl border border-amber-200 bg-amber-50/60 p-4 hover:bg-amber-50 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-amber-700 uppercase">Menunggu Persetujuan</p>
                    <p class="text-2xl font-black text-amber-900 mt-1">{{ $pendingCount }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 8.25h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permissions.index', ['status' => 'disetujui']) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-4 hover:bg-emerald-50 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-emerald-700 uppercase">Telah Disetujui</p>
                    <p class="text-2xl font-black text-emerald-900 mt-1">{{ $approvedCount }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.permissions.index', ['status' => 'ditolak']) }}" class="rounded-2xl border border-rose-200 bg-rose-50/60 p-4 hover:bg-rose-50 transition-colors">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-rose-700 uppercase">Ditolak</p>
                    <p class="text-2xl font-black text-rose-900 mt-1">{{ $rejectedCount }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.permissions.index') }}" class="flex flex-wrap items-center gap-3">
            <select name="status" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select name="type" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <option value="">Semua Jenis</option>
                <option value="izin" {{ request('type') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('type') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                Filter
            </button>
            @if (request()->hasAny(['status', 'type']))
                <a href="{{ route('admin.permissions.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Permissions Table -->
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal Izin</th>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4">Kelas</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Alasan</th>
                        <th class="px-6 py-4">Bukti</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($permissions as $perm)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-700">
                                {{ $perm->date->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 flex items-center gap-2.5">
                                <img src="{{ $perm->student->photo_url }}" class="h-7 w-7 rounded-lg object-cover ring-1 ring-slate-200 shrink-0" alt="">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $perm->student->user->name ?? '-' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $perm->student->student_number }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $perm->student->class->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-md bg-slate-100 px-2 py-0.5 font-bold uppercase text-[10px] text-slate-700">
                                    {{ $perm->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 max-w-xs truncate">
                                {{ $perm->reason }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($perm->evidence)
                                    <a href="{{ Storage::url($perm->evidence) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 hover:text-indigo-800 underline">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        Berkas
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($perm->status === 'disetujui')
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Disetujui</span>
                                @elseif ($perm->status === 'menunggu')
                                    <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700 animate-pulse">Menunggu</span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.permissions.show', $perm) }}" class="rounded-xl bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 hover:bg-indigo-100 transition-colors">
                                    Tinjau &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada pengajuan izin yang ditemukan.
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
