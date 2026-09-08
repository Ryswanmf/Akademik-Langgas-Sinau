@extends('layouts.siswa', ['title' => $announcement->title, 'header' => 'Detail Pengumuman'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('siswa.announcements') }}" class="rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
            &larr; Kembali ke Pengumuman
        </a>
        <span class="text-xs text-slate-400">
            Dipublikasikan: {{ $announcement->published_at ? $announcement->published_at->translatedFormat('d F Y, H:i') : '' }}
        </span>
    </div>

    <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-10 shadow-xs space-y-6">
        @if ($announcement->image)
            <img src="{{ Storage::url($announcement->image) }}" class="w-full max-h-96 rounded-2xl object-cover ring-1 ring-slate-100" alt="{{ $announcement->title }}">
        @endif

        <div>
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 uppercase">
                Pengumuman Resmi
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-3 leading-tight">
                {{ $announcement->title }}
            </h1>
        </div>

        <div class="text-sm text-slate-700 leading-relaxed space-y-4 whitespace-pre-line border-t border-slate-100 pt-6">
            {{ $announcement->content }}
        </div>
    </div>
</div>
@endsection
