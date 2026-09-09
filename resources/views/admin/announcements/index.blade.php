@extends('layouts.admin', ['title' => 'Pengumuman', 'header' => 'Kelola Pengumuman'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Informasi & Pengumuman</h2>
            <p class="text-xs text-slate-500">Buat pengumuman akademik yang akan tampil pada dashboard siswa.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-700 transition-colors">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Buat Pengumuman Baru
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="rounded-2xl border border-slate-200/80 bg-white p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="flex flex-wrap items-center gap-3">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari judul atau isi pengumuman..." 
                class="w-full sm:w-72 rounded-xl border border-slate-200 px-3.5 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
            >
            <select name="status" class="rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white hover:bg-slate-900 transition-colors">
                Filter
            </button>
            @if (request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.announcements.index') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Announcements Cards List -->
    <div class="space-y-4">
        @forelse ($announcements as $item)
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    @if ($item->image)
                        <img src="{{ $item->image_url ?? Storage::url($item->image) }}" class="h-16 w-16 rounded-2xl object-cover ring-1 ring-slate-100 shrink-0" alt="">
                    @else
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600 shrink-0">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.213m3.102 0a41.442 41.442 0 007.82 2.658c.84.184 1.64-.442 1.64-1.303V5.424c0-.861-.8-1.487-1.64-1.303a41.439 41.439 0 00-7.82 2.658m0 9.18V6.84" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase
                                {{ $item->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $item->status }}
                            </span>
                            <span class="text-[11px] text-slate-400">
                                {{ $item->published_at ? $item->published_at->translatedFormat('d F Y, H:i') : 'Draft' }}
                            </span>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-800">{{ $item->title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed line-clamp-2">{{ $item->content }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                    <a href="{{ route('admin.announcements.edit', $item) }}" title="Edit" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 hover:bg-slate-50 hover:text-amber-600 shadow-2xs">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    <div x-data="{ openDelete: false }">
                        <button @click="openDelete = true" title="Hapus" class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 hover:bg-rose-50 hover:text-rose-600 shadow-2xs">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>

                        <div x-show="openDelete" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="openDelete = false"></div>
                            <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl text-left border border-slate-100 z-10">
                                <h3 class="text-base font-bold text-slate-900">Hapus Pengumuman?</h3>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                    Hapus pengumuman <strong>{{ $item->title }}</strong>? Pengumuman tidak akan lagi ditampilkan pada dashboard siswa.
                                </p>
                                <div class="mt-6 flex justify-end gap-2.5">
                                    <button type="button" @click="openDelete = false" class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                                    <form action="{{ route('admin.announcements.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 shadow-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center text-xs text-slate-400">
                Belum ada pengumuman yang dibuat.
            </div>
        @endforelse
    </div>

    @if ($announcements->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
