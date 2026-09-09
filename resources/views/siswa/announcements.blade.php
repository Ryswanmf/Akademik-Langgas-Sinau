@extends('layouts.siswa', ['title' => 'Pengumuman', 'header' => 'Pengumuman & Berita'])

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Papan Pengumuman Akademi</h2>
        <p class="text-xs text-slate-500">Informasi terbaru, pengumuman ujian, jadwal libur, dan event pelatihan.</p>
    </div>

    <!-- Announcements List -->
    <div class="space-y-4">
        @forelse ($announcements as $ann)
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs flex flex-col md:flex-row gap-6 hover:shadow-md transition-shadow">
                @if ($ann->image)
                    <img src="{{ $ann->image_url ?? Storage::url($ann->image) }}" class="h-44 w-full md:w-56 rounded-2xl object-cover ring-1 ring-slate-100 shrink-0" alt="{{ $ann->title }}">
                @endif
                <div class="space-y-2 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 uppercase">
                                Pengumuman
                            </span>
                            <span class="text-xs text-slate-400">
                                {{ $ann->published_at ? $ann->published_at->translatedFormat('d F Y, H:i') : '' }}
                            </span>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-800">
                            {{ $ann->title }}
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">
                            {{ $ann->content }}
                        </p>
                    </div>

                    <div class="pt-3">
                        <a href="{{ route('siswa.announcements.show', $ann) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 hover:text-emerald-800">
                            <span>Baca Selengkapnya</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center text-xs text-slate-400">
                Belum ada pengumuman yang dipublikasikan.
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
