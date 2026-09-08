<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transkrip Nilai & Sertifikat - {{ $certificate->student->user->name }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background: #f8fafc;
            font-size: 12px;
        }
        .page-container {
            max-width: 210mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 30px 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            border-radius: 8px;
        }
        @media print {
            body {
                background: #ffffff;
            }
            .page-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        .header-kop {
            display: flex;
            align-items: center;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
            gap: 16px;
        }
        .header-kop img {
            height: 70px;
            width: 70px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            flex: 1;
        }
        .kop-title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin: 0;
        }
        .kop-sub {
            font-size: 13px;
            font-weight: 700;
            color: #4338ca;
            margin: 2px 0;
        }
        .kop-desc {
            font-size: 10px;
            color: #64748b;
            margin: 0;
        }
        .doc-title {
            text-align: center;
            margin: 20px 0 16px 0;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: underline;
        }
        .doc-title p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #475569;
            font-family: monospace;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 24px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
            font-size: 11px;
        }
        .info-label {
            width: 130px;
            font-weight: 600;
            color: #64748b;
        }
        .info-val {
            font-weight: 700;
            color: #1e293b;
            flex: 1;
        }
        table.score-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        table.score-table th, table.score-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
        }
        table.score-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: 700 !important; }
        .bg-summary {
            background-color: #f8fafc;
            font-weight: 700;
        }
        .badge-predicate {
            display: inline-block;
            background: #ede9fe;
            color: #5b21b6;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 800;
            font-size: 11px;
        }
        .notes-box {
            border: 1px dashed #cbd5e1;
            background: #fff;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 11px;
        }
        .notes-title {
            font-weight: 700;
            color: #475569;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 10px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 20px;
        }
        .sign-col {
            text-align: center;
            width: 200px;
            font-size: 11px;
        }
        .sign-space {
            height: 65px;
        }
        .sign-name {
            font-weight: 700;
            border-bottom: 1px solid #1e293b;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
        .toolbar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }
        .btn-print {
            background: #4f46e5;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover { background: #4338ca; }
        .btn-close {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Floating Action Bar for Web View -->
    <div class="toolbar no-print">
        <button class="btn-close" onclick="window.close()">Tutup</button>
        <button class="btn-print" onclick="window.print()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4"></path>
            </svg>
            Cetak Transkrip
        </button>
    </div>

    <div class="page-container">
        <!-- Kop Surat Lembaga -->
        <div class="header-kop">
            <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo LKP Langgas Sinau">
            <div class="kop-text">
                <h1 class="kop-title">LEMBAGA KURSUS & PELATIHAN (LKP) LANGGAS SINAU</h1>
                <p class="kop-sub">AKADEMI KEJURUAN & PUSAT PENGEMBANGAN TEKNOLOGI</p>
                <p class="kop-desc">
                    Gg. Cendana, Banjar Rejo, Kec. Batanghari, Kab. Lampung Timur, Lampung 34181<br>
                    Website: langgas-sinau.com • Email: info@langgas-sinau.com • Telp/WA: 0812-3456-7890
                </p>
            </div>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            <h2>TRANSKRIP PENILAIAN & SERTIFIKAT KOMPETENSI</h2>
            <p>NOMOR: {{ $certificate->certificate_number }}</p>
        </div>

        <!-- Identitas Siswa -->
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Nama Siswa</span>
                <span class="info-val">: {{ $certificate->student->user->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Program Keahlian</span>
                <span class="info-val">: {{ $certificate->program }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Nomor Induk (NIS)</span>
                <span class="info-val">: {{ $certificate->student->student_number }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Asal Instansi/Sekolah</span>
                <span class="info-val">: {{ $certificate->student->school_origin ?: '-' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Judul Sertifikat</span>
                <span class="info-val">: {{ $certificate->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tanggal Terbit</span>
                <span class="info-val">: {{ $certificate->issued_date ? $certificate->issued_date->translatedFormat('d F Y') : '-' }}</span>
            </div>
        </div>

        <!-- Tabel 6 Komponen Nilai -->
        <table class="score-table">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th>Komponen Penilaian & Evaluasi Kompetensi</th>
                    <th style="width: 100px;" class="text-center">Skala Nilai</th>
                    <th style="width: 100px;" class="text-center">Nilai Angka</th>
                    <th style="width: 130px;" class="text-center">Kategori</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. Disiplin -->
                <tr>
                    <td class="text-center font-bold">1</td>
                    <td>
                        <strong>Disiplin</strong>
                        <div style="font-size: 9.5px; color: #64748b;">Ketaatan terhadap waktu masuk/pulang, tata tertib, dan instruksi pembelajaran.</div>
                    </td>
                    <td class="text-center">0 - 100</td>
                    <td class="text-center font-bold">{{ $certificate->score_discipline ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Certificate::determinePredicate($certificate->score_discipline) }}</td>
                </tr>

                <!-- 2. Inisiatif & Kreatifitas -->
                <tr>
                    <td class="text-center font-bold">2</td>
                    <td>
                        <strong>Inisiatif & Kreatifitas</strong>
                        <div style="font-size: 9.5px; color: #64748b;">Daya cipta, kemandirian problem solving, dan inovasi dalam implementasi project.</div>
                    </td>
                    <td class="text-center">0 - 100</td>
                    <td class="text-center font-bold">{{ $certificate->score_initiative ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Certificate::determinePredicate($certificate->score_initiative) }}</td>
                </tr>

                <!-- 3. Kerja sama -->
                <tr>
                    <td class="text-center font-bold">3</td>
                    <td>
                        <strong>Kerja sama</strong>
                        <div style="font-size: 9.5px; color: #64748b;">Kemampuan komunikasi, koordinasi, dan kontribusi aktif di dalam tim kerja.</div>
                    </td>
                    <td class="text-center">0 - 100</td>
                    <td class="text-center font-bold">{{ $certificate->score_teamwork ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Certificate::determinePredicate($certificate->score_teamwork) }}</td>
                </tr>

                <!-- 4. Tanggung Jawab -->
                <tr>
                    <td class="text-center font-bold">4</td>
                    <td>
                        <strong>Tanggung Jawab</strong>
                        <div style="font-size: 9.5px; color: #64748b;">Komitmen menyelesaikan seluruh penugasan secara tuntas, teliti, dan tepat waktu.</div>
                    </td>
                    <td class="text-center">0 - 100</td>
                    <td class="text-center font-bold">{{ $certificate->score_responsibility ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Certificate::determinePredicate($certificate->score_responsibility) }}</td>
                </tr>

                <!-- 5. Sikap -->
                <tr>
                    <td class="text-center font-bold">5</td>
                    <td>
                        <strong>Sikap (Attitude)</strong>
                        <div style="font-size: 9.5px; color: #64748b;">Etika kerja profesional, sopan santun, kejujuran, dan integritas kepribadian.</div>
                    </td>
                    <td class="text-center">0 - 100</td>
                    <td class="text-center font-bold">{{ $certificate->score_attitude ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Certificate::determinePredicate($certificate->score_attitude) }}</td>
                </tr>

                <!-- 6. Kehadiran -->
                <tr>
                    <td class="text-center font-bold">6</td>
                    <td>
                        <strong>Kehadiran (Presensi)</strong>
                        <div style="font-size: 9.5px; color: #64748b;">Konsistensi kehadiran pembelajaran teori/praktik dan ketepatan waktu presensi digital.</div>
                    </td>
                    <td class="text-center">0 - 100</td>
                    <td class="text-center font-bold">{{ $certificate->score_attendance ?? '-' }}</td>
                    <td class="text-center">{{ \App\Models\Certificate::determinePredicate($certificate->score_attendance) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-summary">
                    <td colspan="3" class="text-right" style="padding-right: 16px; font-size: 11px;">
                        NILAI RATA-RATA AKHIR:
                    </td>
                    <td class="text-center font-bold" style="font-size: 13px; color: #1e1b4b;">
                        {{ $certificate->final_score !== null ? number_format($certificate->final_score, 1) : '-' }}
                    </td>
                    <td class="text-center">
                        <span class="badge-predicate">
                            {{ $certificate->grade_predicate ?: '-' }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Catatan Evaluasi Mentor -->
        @if ($certificate->assessment_notes)
            <div class="notes-box">
                <div class="notes-title">Catatan / Rekomendasi Mentor:</div>
                <div style="line-height: 1.5; color: #334155;">
                    {{ $certificate->assessment_notes }}
                </div>
            </div>
        @endif

        <!-- Area Pengesahan Tanda Tangan -->
        <div class="signature-row">
            <div class="sign-col">
                <p>Mengetahui,<br>Siswa yang Bersangkutan</p>
                <div class="sign-space"></div>
                <p class="sign-name">{{ $certificate->student->user->name }}</p>
                <p style="margin: 0; font-size: 10px; color: #64748b;">NIS: {{ $certificate->student->student_number }}</p>
            </div>

            <div class="sign-col">
                <p>Batanghari, {{ $certificate->issued_date ? $certificate->issued_date->translatedFormat('d F Y') : date('d F Y') }}<br>Pimpinan LKP Langgas Sinau</p>
                <div class="sign-space"></div>
                <p class="sign-name">{{ $certificate->leader_name ?: 'Pimpinan LKP Langgas Sinau' }}</p>
                <p style="margin: 0; font-size: 10px; color: #64748b;">NIP. LKP-LS-{{ date('Y') }}-DIR</p>
            </div>
        </div>
    </div>

</body>
</html>
