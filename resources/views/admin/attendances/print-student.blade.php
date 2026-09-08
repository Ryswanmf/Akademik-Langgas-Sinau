<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi - {{ $student->user->name }} ({{ $student->student_number }})</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                padding: 0 !important;
            }
            .page-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased p-4 sm:p-8">

    <!-- Top Floating Toolbar (Hidden when Printing) -->
    <div class="no-print max-w-4xl mx-auto mb-6 bg-white rounded-2xl p-4 shadow-md border border-slate-200 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ url()->previous() ?: route('admin.attendances.rekap') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-xs font-bold text-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
            <span class="text-xs text-slate-500 font-medium">Pratinjau Cetak Lembar Absensi Siswa</span>
        </div>
        <div class="flex items-center gap-2">
            <button 
                onclick="window.print()" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-md shadow-indigo-600/30 transition-all cursor-pointer active:scale-95"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4" />
                </svg>
                <span>Cetak / Simpan PDF (Ctrl+P)</span>
            </button>
        </div>
    </div>

    <!-- Printable A4 Paper Container -->
    <div class="page-container max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-xl border border-slate-200/80">
        
        <!-- Kop Surat Lembaga LKP Langgas Sinau -->
        <div class="flex items-center gap-6 pb-5 border-b-2 border-slate-900">
            <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo LKP Langgas Sinau" class="w-20 h-20 sm:w-24 sm:h-24 object-contain shrink-0">
            <div class="flex-1 text-center sm:text-left">
                <span class="text-[11px] font-bold tracking-widest text-indigo-700 uppercase">Lembaga Kursus & Pelatihan Resmi</span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 uppercase tracking-tight leading-tight">
                    LKP LANGGAS SINAU AKADEMI
                </h1>
                <p class="text-xs font-bold text-emerald-600 italic">“Berdikari Mengenal Diri”</p>
                <p class="text-[11px] text-slate-600 mt-1 leading-snug">
                    Pusat Pelatihan Vokasi, Keterampilan Kerja & Kemandirian Siswa<br>
                    Alamat: Gg. Cendana, Banjar Rejo, Kec. Batanghari, Kab. Lampung Timur, Lampung 34181<br>
                    Website: https://langgas-sinau.com • WhatsApp: +62 812-3456-7890 • Email: admin@langgas-sinau.com
                </p>
            </div>
        </div>
        <div class="border-b border-slate-400 mt-0.5 mb-6"></div>

        <!-- Judul Dokumen -->
        <div class="text-center my-6">
            <h2 class="text-lg sm:text-xl font-extrabold uppercase text-slate-900 tracking-wider">
                LEMBAR REKAPITULASI HASIL ABSENSI SISWA
            </h2>
            <p class="text-xs text-slate-500 font-mono mt-1">
                No. Dokumen: REC-ABS/LS/{{ date('Y') }}/{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <!-- Identitas Siswa -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs mb-6">
            <div class="space-y-1.5">
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Nama Lengkap</span>
                    <span class="text-slate-900 font-extrabold">: {{ $student->user->name }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Nomor Siswa (NIS)</span>
                    <span class="text-slate-900 font-mono font-bold">: {{ $student->student_number }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Program Kursus</span>
                    <span class="text-slate-900 font-semibold">: {{ $student->program }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Asal Sekolah</span>
                    <span class="text-slate-900 font-semibold">: {{ $student->school_origin ?: '-' }}</span>
                </div>
            </div>
            <div class="space-y-1.5">
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Status Siswa</span>
                    <span class="text-slate-900 font-bold uppercase">: {{ $student->status }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Tanggal Terdaftar</span>
                    <span class="text-slate-900">: {{ $student->entry_date ? $student->entry_date->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 font-bold text-slate-600">Waktu Dicetak</span>
                    <span class="text-slate-900">: {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <!-- Ringkasan Statistik Presensi -->
        <div class="grid grid-cols-7 gap-2 text-center text-xs mb-6">
            <div class="p-3 rounded-xl border border-slate-200 bg-slate-50">
                <p class="text-[10px] font-bold text-slate-500 uppercase">Total Sesi</p>
                <p class="text-xl font-black text-slate-900 mt-0.5">{{ $stats['total'] }}</p>
            </div>
            <div class="p-3 rounded-xl border border-emerald-200 bg-emerald-50">
                <p class="text-[10px] font-bold text-emerald-700 uppercase">Hadir</p>
                <p class="text-xl font-black text-emerald-700 mt-0.5">{{ $stats['hadir'] }}</p>
            </div>
            <div class="p-3 rounded-xl border border-amber-200 bg-amber-50">
                <p class="text-[10px] font-bold text-amber-700 uppercase">Terlambat</p>
                <p class="text-xl font-black text-amber-700 mt-0.5">{{ $stats['terlambat'] }}</p>
            </div>
            <div class="p-3 rounded-xl border border-blue-200 bg-blue-50">
                <p class="text-[10px] font-bold text-blue-700 uppercase">Izin</p>
                <p class="text-xl font-black text-blue-700 mt-0.5">{{ $stats['izin'] }}</p>
            </div>
            <div class="p-3 rounded-xl border border-purple-200 bg-purple-50">
                <p class="text-[10px] font-bold text-purple-700 uppercase">Sakit</p>
                <p class="text-xl font-black text-purple-700 mt-0.5">{{ $stats['sakit'] }}</p>
            </div>
            <div class="p-3 rounded-xl border border-rose-200 bg-rose-50">
                <p class="text-[10px] font-bold text-rose-700 uppercase">Alpa</p>
                <p class="text-xl font-black text-rose-700 mt-0.5">{{ $stats['alpa'] }}</p>
            </div>
            <div class="p-3 rounded-xl border border-indigo-200 bg-indigo-50">
                <p class="text-[10px] font-bold text-indigo-700 uppercase">Kehadiran</p>
                <p class="text-xl font-black text-indigo-700 mt-0.5">{{ $stats['rate'] }}%</p>
            </div>
        </div>

        <!-- Tabel Rincian Kehadiran -->
        <div class="border border-slate-200 rounded-xl overflow-hidden mb-8">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-700 font-bold uppercase text-[10px] border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-2.5 w-10 text-center">No</th>
                        <th class="px-3 py-2.5">Tanggal</th>
                        <th class="px-3 py-2.5">Sesi / Jadwal</th>
                        <th class="px-3 py-2.5 text-center">Masuk</th>
                        <th class="px-3 py-2.5 text-center">Pulang</th>
                        <th class="px-3 py-2.5 text-center">Jarak LKP</th>
                        <th class="px-3 py-2.5 text-center">Status</th>
                        <th class="px-3 py-2.5">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($attendances as $index => $att)
                        <tr class="{{ $index % 2 === 1 ? 'bg-slate-50/60' : 'bg-white' }}">
                            <td class="px-3 py-2 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-3 py-2 font-medium text-slate-900 whitespace-nowrap">
                                {{ $att->date->translatedFormat('d/m/Y') }}
                            </td>
                            <td class="px-3 py-2 text-slate-700 font-medium">
                                {{ $att->schedule->title ?? 'Sesi Pembelajaran Mandiri' }}
                            </td>
                            <td class="px-3 py-2 text-center font-mono font-bold text-slate-800 whitespace-nowrap">
                                {{ $att->formatted_check_in_time }}
                            </td>
                            <td class="px-3 py-2 text-center font-mono font-bold text-slate-800 whitespace-nowrap">
                                {{ $att->formatted_check_out_time }}
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                @if ($att->check_in_distance !== null)
                                    <span class="font-semibold {{ $att->is_check_in_within_radius ? 'text-emerald-700' : 'text-amber-700' }}">
                                        {{ $att->check_in_distance_formatted }}
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                @if ($att->status === 'hadir')
                                     <span class="inline-block px-2 py-0.5 rounded-md font-bold text-[10px] bg-emerald-100 text-emerald-800 uppercase">HADIR</span>
                                 @elseif ($att->status === 'terlambat')
                                     <span class="inline-block px-2 py-0.5 rounded-md font-bold text-[10px] bg-amber-100 text-amber-800 uppercase">TERLAMBAT</span>
                                 @elseif ($att->status === 'izin')
                                     <span class="inline-block px-2 py-0.5 rounded-md font-bold text-[10px] bg-blue-100 text-blue-800 uppercase">IZIN</span>
                                 @elseif ($att->status === 'sakit')
                                     <span class="inline-block px-2 py-0.5 rounded-md font-bold text-[10px] bg-amber-100 text-amber-800 uppercase">SAKIT</span>
                                 @else
                                     <span class="inline-block px-2 py-0.5 rounded-md font-bold text-[10px] bg-rose-100 text-rose-800 uppercase">ALPA</span>
                                 @endif
                            </td>
                            <td class="px-3 py-2 text-slate-600 italic">
                                {{ $att->note ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                                Belum ada riwayat catatan absensi untuk siswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Kolom Pengesahan / Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 pt-6 text-xs text-center border-t border-slate-200">
            <div>
                <p class="text-slate-500 mb-16">Siswa yang Bersangkutan,</p>
                <p class="font-bold text-slate-900 underline">{{ $student->user->name }}</p>
                <p class="text-[11px] text-slate-500 font-mono">NIS: {{ $student->student_number }}</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">
                    Mengetahui,<br>
                    <strong>Administrator LKP Langgas Sinau</strong>
                </p>
                <p class="font-bold text-slate-900 underline">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-slate-500">NIP / ID: {{ auth()->user()->id }}</p>
            </div>
        </div>

    </div>

</body>
</html>
