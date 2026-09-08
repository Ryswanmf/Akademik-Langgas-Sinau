@extends('layouts.siswa', ['title' => 'Jadwal Saya', 'header' => 'Jadwal Kelas Saya'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Jadwal Sesi Pembelajaran</h2>
            <p class="text-xs text-slate-500">Jadwal sesi kelas {{ $student->class->name ?? '-' }} ({{ $student->program }}).</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('siswa.schedules') }}" class="flex gap-2 max-w-md">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari materi atau ruangan..." 
                class="w-full rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20"
            >
            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                Cari
            </button>
            @if (request('search'))
                <a href="{{ route('siswa.schedules') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Schedules Table -->
    <div class="rounded-3xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider text-[10px] font-bold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Materi / Sesi</th>
                        <th class="px-6 py-4">Hari & Tanggal</th>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Ruangan</th>
                        <th class="px-6 py-4">Instruksi & Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($schedules as $sched)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">
                                {{ $sched->title }}
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ $sched->date->translatedFormat('l, d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600">
                                {{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }} WIB
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                    {{ $sched->room }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 max-w-sm">
                                {{ $sched->description ?? 'Tidak ada instruksi khusus.' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada jadwal yang ditemukan untuk kelas Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($schedules, 'hasPages') && $schedules->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
