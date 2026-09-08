@extends('layouts.siswa', ['title' => 'Nilai & Sertifikat Saya', 'header' => 'Nilai & Sertifikat'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Nilai & Sertifikat Kompetensi</h2>
            <p class="text-xs text-slate-500">Transkrip hasil penilaian 6 aspek kompetensi dan berkas sertifikat resmi yang telah diterbitkan oleh LKP Langgas Sinau.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 border border-emerald-200/60">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Terverifikasi LKP
            </span>
        </div>
    </div>

    <!-- Certificates & Grades List -->
    <div class="space-y-6">
        @forelse ($certificates as $cert)
            <div class="rounded-3xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-xs hover:shadow-md transition-shadow relative overflow-hidden space-y-6">
                <!-- Top Ribbon -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-700 text-white shrink-0 shadow-md shadow-emerald-500/20">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                            </svg>
                        </div>
                        <div>
                            <span class="inline-flex rounded-full bg-purple-50 px-2.5 py-0.5 text-[10px] font-bold text-purple-700 mb-1">
                                Resmi Terpublikasi
                            </span>
                            <h3 class="text-lg font-black text-slate-800 leading-snug">{{ $cert->name }}</h3>
                            <p class="text-xs text-emerald-700 font-semibold mt-0.5">Program Keahlian: {{ $cert->program }}</p>
                            <p class="text-[11px] text-slate-400 font-mono mt-0.5">No. Sertifikat: {{ $cert->certificate_number }}</p>
                        </div>
                    </div>

                    <!-- Final Score Card -->
                    @if ($cert->final_score !== null)
                        <div class="flex items-center sm:flex-col items-start sm:items-end justify-between bg-slate-50 sm:bg-transparent p-3 sm:p-0 rounded-2xl border sm:border-0 border-slate-100">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Nilai Akhir Rata-rata</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-3xl font-black text-emerald-700">{{ number_format($cert->final_score, 1) }}</span>
                                <span class="rounded-xl bg-purple-100 text-purple-800 px-2.5 py-1 text-xs font-black">
                                    {{ $cert->grade_predicate ?: 'Lulus' }}
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400 mt-0.5">Diterbitkan: {{ $cert->issued_date ? $cert->issued_date->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                    @endif
                </div>

                <!-- 6 Evaluation Criteria Grid -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Rincian 6 Komponen Nilai Siswa
                        </h4>
                        <span class="text-[10px] font-semibold text-slate-400">Standar Penilaian LKP Langgas Sinau</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                        <!-- 1. Disiplin -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">1. Disiplin</span>
                                <p class="text-[10px] text-slate-500 mt-0.5">Ketaatan waktu & aturan</p>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-slate-200/60 pt-2">
                                <span class="text-xl font-black text-slate-800">{{ $cert->score_discipline ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-indigo-600">{{ \App\Models\Certificate::determinePredicate($cert->score_discipline) }}</span>
                            </div>
                        </div>

                        <!-- 2. Inisiatif & Kreatifitas -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">2. Inisiatif</span>
                                <p class="text-[10px] text-slate-500 mt-0.5">Inisiatif & Kreatifitas</p>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-slate-200/60 pt-2">
                                <span class="text-xl font-black text-slate-800">{{ $cert->score_initiative ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-indigo-600">{{ \App\Models\Certificate::determinePredicate($cert->score_initiative) }}</span>
                            </div>
                        </div>

                        <!-- 3. Kerja sama -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">3. Kerja sama</span>
                                <p class="text-[10px] text-slate-500 mt-0.5">Komunikasi & tim kerja</p>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-slate-200/60 pt-2">
                                <span class="text-xl font-black text-slate-800">{{ $cert->score_teamwork ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-indigo-600">{{ \App\Models\Certificate::determinePredicate($cert->score_teamwork) }}</span>
                            </div>
                        </div>

                        <!-- 4. Tanggung Jawab -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">4. T. Jawab</span>
                                <p class="text-[10px] text-slate-500 mt-0.5">Komitmen tuntas tugas</p>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-slate-200/60 pt-2">
                                <span class="text-xl font-black text-slate-800">{{ $cert->score_responsibility ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-indigo-600">{{ \App\Models\Certificate::determinePredicate($cert->score_responsibility) }}</span>
                            </div>
                        </div>

                        <!-- 5. Sikap -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">5. Sikap</span>
                                <p class="text-[10px] text-slate-500 mt-0.5">Etika, adab & integritas</p>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-slate-200/60 pt-2">
                                <span class="text-xl font-black text-slate-800">{{ $cert->score_attitude ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-indigo-600">{{ \App\Models\Certificate::determinePredicate($cert->score_attitude) }}</span>
                            </div>
                        </div>

                        <!-- 6. Kehadiran -->
                        <div class="rounded-2xl border border-slate-100 bg-slate-50/70 p-3.5 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">6. Kehadiran</span>
                                <p class="text-[10px] text-slate-500 mt-0.5">Konsistensi presensi</p>
                            </div>
                            <div class="mt-3 flex items-baseline justify-between border-t border-slate-200/60 pt-2">
                                <span class="text-xl font-black text-slate-800">{{ $cert->score_attendance ?? '-' }}</span>
                                <span class="text-[10px] font-bold text-indigo-600">{{ \App\Models\Certificate::determinePredicate($cert->score_attendance) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mentor Feedback / Notes -->
                @if ($cert->assessment_notes)
                    <div class="rounded-2xl bg-emerald-50/50 border border-emerald-100 p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 shrink-0 mt-0.5">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.502 48.17 48.17 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-emerald-900">Catatan & Evaluasi Mentor</p>
                                <p class="text-xs text-emerald-800/90 mt-1 leading-relaxed">{{ $cert->assessment_notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Bottom Action Footer -->
                <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span class="text-[11px] text-slate-400">Penerbit: LKP Langgas Sinau • Batanghari, Lampung Timur</span>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <!-- Buka & Cetak Sertifikat Resmi Lanskap -->
                        <a href="{{ route('siswa.certificates.certificate', $cert) }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-amber-500/20 hover:from-amber-600 hover:to-amber-700 transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.004 0V5.625c0-.621-.504-1.125-1.125-1.125H9.375c-.621 0-1.125.504-1.125 1.125v8.625" />
                            </svg>
                            Lihat & Cetak Sertifikat Resmi
                        </a>

                        <!-- Cetak Transkrip Nilai -->
                        <a href="{{ route('siswa.certificates.print', $cert) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                            <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                            </svg>
                            Cetak Transkrip Nilai
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State when NOT published yet -->
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white p-12 text-center max-w-xl mx-auto space-y-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-amber-50 text-amber-600 mx-auto">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-800">Nilai & Sertifikat Belum Dipublikasikan</h3>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-md mx-auto">
                        Data evaluasi penilaian 6 komponen kompetensi (Disiplin, Inisiatif & Kreatifitas, Kerja sama, Tanggung Jawab, Sikap, Kehadiran) dan sertifikat Anda saat ini <strong>belum dipublikasikan</strong> oleh pihak Administrator LKP Langgas Sinau.
                    </p>
                </div>
                <div class="pt-2">
                    <p class="text-[11px] text-slate-400">
                        Hasil evaluasi akan otomatis tampil di halaman ini segera setelah verifikasi dan penerbitan resmi disetujui oleh admin.
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
