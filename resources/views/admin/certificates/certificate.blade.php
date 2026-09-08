<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi - {{ $certificate->student->user->name }} - {{ $certificate->certificate_number }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    
    <!-- Google Fonts: Playfair Display, Cinzel, Plus Jakarta Sans, Pinyon Script -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Great+Vibes&family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #0b1120;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Floating Toolbar (Hidden during Print) */
        .toolbar {
            position: fixed;
            top: 16px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            padding: 10px 18px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-print {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
        }
        .btn-print:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        .btn-transcript {
            background: #ffffff;
            color: #0f172a;
        }
        .btn-transcript:hover {
            background: #f1f5f9;
        }

        .btn-back {
            background: transparent;
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .print-tip {
            color: #94a3b8;
            font-size: 11px;
            display: none;
        }
        @media (min-width: 900px) {
            .print-tip { display: block; }
        }

        /* Certificate Container (A4 Landscape: 297mm x 210mm) */
        .cert-container {
            width: 297mm;
            height: 210mm;
            margin: 40px auto;
            position: relative;
            background: #fdfbf7;
            padding: 10mm;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            page-break-after: always;
        }

        @media print {
            body {
                background: transparent;
            }
            .toolbar {
                display: none !important;
            }
            .cert-container {
                margin: 0 !important;
                box-shadow: none !important;
                width: 297mm !important;
                height: 210mm !important;
                padding: 10mm !important;
            }
        }

        /* Decorative Borders & Guilloche Frame */
        .cert-outer-border {
            position: relative;
            width: 100%;
            height: 100%;
            border: 3px solid #b45309;
            padding: 3mm;
            background: #ffffff;
        }

        .cert-middle-border {
            position: relative;
            width: 100%;
            height: 100%;
            border: 1px dashed #d97706;
            padding: 4mm;
            background: 
                radial-gradient(circle at center, rgba(254, 243, 199, 0.35) 0%, rgba(255, 255, 255, 0) 70%),
                repeating-radial-gradient(circle, transparent, transparent 15px, rgba(217, 119, 6, 0.018) 15px, rgba(217, 119, 6, 0.018) 30px);
        }

        .cert-inner-border {
            position: relative;
            width: 100%;
            height: 100%;
            border: 2px solid #0f172a;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 12px 32px 14px 32px;
            background: rgba(255, 255, 255, 0.85);
        }

        /* Ornate Corner SVG Accents */
        .corner-ornament {
            position: absolute;
            width: 42px;
            height: 42px;
            color: #b45309;
            pointer-events: none;
            z-index: 5;
        }
        .corner-tl { top: -2px; left: -2px; }
        .corner-tr { top: -2px; right: -2px; transform: scaleX(-1); }
        .corner-bl { bottom: -2px; left: -2px; transform: scaleY(-1); }
        .corner-br { bottom: -2px; right: -2px; transform: scale(-1, -1); }

        /* Header Elements */
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 10px;
            position: relative;
        }

        .cert-logo {
            height: 58px;
            width: 58px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .header-title-box {
            text-align: center;
        }

        .inst-title {
            font-family: 'Cinzel', serif;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 1.5px;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }

        .inst-subtitle {
            font-size: 10.5px;
            font-weight: 700;
            color: #047857;
            letter-spacing: 0.5px;
            margin: 2px 0 0 0;
            text-transform: uppercase;
        }

        .inst-legal {
            font-size: 8.5px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        /* Certificate Titles */
        .cert-heading-area {
            text-align: center;
            margin-top: 8px;
        }

        .main-title {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 3px;
            color: #b45309;
            margin: 0;
            text-transform: uppercase;
            background: linear-gradient(135deg, #92400e 0%, #b45309 50%, #78350f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 8px rgba(180, 83, 9, 0.15);
        }

        .sub-title {
            font-family: 'Playfair Display', serif;
            font-size: 11px;
            letter-spacing: 2px;
            color: #475569;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            font-style: italic;
        }

        .reg-number-pill {
            display: inline-block;
            margin-top: 4px;
            font-family: monospace;
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 2px 12px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
        }

        /* Recipient Section */
        .recipient-section {
            text-align: center;
            margin: 8px 0;
        }

        .recipient-prefix {
            font-family: 'Playfair Display', serif;
            font-size: 12px;
            font-style: italic;
            color: #64748b;
            margin: 0;
        }

        .recipient-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin: 4px 0 2px 0;
            letter-spacing: 0.5px;
            text-decoration: underline;
            text-decoration-color: #d97706;
            text-underline-offset: 6px;
            text-decoration-thickness: 1.5px;
        }

        .recipient-meta {
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            margin: 6px 0 0 0;
        }

        /* Statement of Competence */
        .statement-box {
            text-align: center;
            max-width: 85%;
            margin: 0 auto;
        }

        .statement-text {
            font-size: 11px;
            line-height: 1.55;
            color: #334155;
            margin: 0;
        }

        .course-highlight {
            font-size: 14.5px;
            font-weight: 800;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin: 4px 0;
        }

        .score-pill-row {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 3px 14px;
            border-radius: 9999px;
            margin-top: 4px;
            font-size: 11px;
        }

        /* Footer & Signatures */
        .cert-footer {
            display: grid;
            grid-template-columns: 1fr 140px 1fr;
            align-items: end;
            margin-top: 4px;
            padding-top: 2px;
        }

        .sign-block {
            text-align: center;
        }

        .sign-role {
            font-size: 10.5px;
            font-weight: 600;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }

        .sign-space {
            height: 75px;
            position: relative;
        }

        .sign-name {
            font-size: 12px;
            font-weight: 800;
            color: #0f172a;
            border-top: 1.5px solid #0f172a;
            padding-top: 4px;
            margin: 0 auto;
            width: 80%;
        }

        .sign-nip {
            font-size: 9.5px;
            color: #64748b;
            font-family: monospace;
            margin: 2px 0 0 0;
        }

        /* Center Medallion & Official Stamp */
        .center-seal-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            padding-bottom: 6px;
        }

        .gold-seal-badge {
            width: 80px;
            height: 80px;
            position: relative;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
        }

        .rubber-stamp {
            position: absolute;
            top: -12px;
            right: -26px;
            width: 82px;
            height: 82px;
            border-radius: 50%;
            border: 2.5px solid #2563eb;
            color: #2563eb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: rotate(-10deg);
            opacity: 0.85;
            pointer-events: none;
            box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.4);
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, rgba(255,255,255,0) 70%);
        }

        .stamp-text-top {
            font-size: 6px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }
        .stamp-text-center {
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 1px solid #2563eb;
            border-bottom: 1px solid #2563eb;
            padding: 1px 4px;
            margin: 1px 0;
        }
        .stamp-text-bottom {
            font-size: 6px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .qr-verify-box {
            margin-top: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 7.5px;
            color: #94a3b8;
        }

        /* ================= PAGE 2: TRANSCRIPT ================= */
        .transcript-page {
            width: 297mm;
            height: 210mm;
            margin: 40px auto;
            position: relative;
            background: #ffffff;
            padding: 10mm;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        @media print {
            .transcript-page {
                margin: 0 !important;
                box-shadow: none !important;
                width: 297mm !important;
                height: 210mm !important;
                padding: 10mm !important;
            }
        }

        .tr-inner-box {
            border: 2px solid #1e293b;
            height: 100%;
            padding: 16px 28px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: 
                radial-gradient(circle at top right, rgba(241, 245, 249, 0.5) 0%, transparent 60%);
        }

        .tr-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 10px;
        }

        .tr-header h3 {
            font-family: 'Cinzel', serif;
            font-size: 16px;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
            letter-spacing: 1px;
        }

        .tr-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 10.5px;
            margin: 10px 0;
        }

        .tr-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }

        .tr-table th, .tr-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
        }

        .tr-table th {
            background: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9.5px;
            letter-spacing: 0.5px;
        }

        .tr-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
    </style>
</head>
<body>

    <!-- Floating Print / Action Bar -->
    <div class="toolbar no-print">
        <span class="print-tip">
            💡 <strong>Tips Cetak:</strong> Pilih Layout <strong>Landscape</strong> & Margins <strong>None</strong>
        </span>

        <button class="btn-action btn-print" onclick="window.print()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.04-.37-2.12-.37-3.229 0-4.639 3.58-8.4 8-8.4s8 3.761 8 8.4c0 1.11-.13 2.189-.37 3.229M3 19.2h18M5.4 19.2v2.4a1.2 1.2 0 001.2 1.2h10.8a1.2 1.2 0 001.2-1.2v-2.4"></path>
            </svg>
            Cetak / Simpan PDF
        </button>

        <a href="{{ auth()->user()->role === 'admin' ? route('admin.certificates.print', $certificate) : route('siswa.certificates.print', $certificate) }}" target="_blank" class="btn-action btn-transcript">
            Lembar Transkrip Saja
        </a>

        <a href="{{ auth()->user()->role === 'admin' ? route('admin.certificates.index') : route('siswa.certificates') }}" class="btn-action btn-back">
            &larr; Kembali
        </a>
    </div>

    <!-- ======================================================= -->
    <!-- HALAMAN 1: SERTIFIKAT RESMI BERBINGKAI EMAS & ORNAMEN   -->
    <!-- ======================================================= -->
    <div class="cert-container">
        <div class="cert-outer-border">
            <div class="cert-middle-border">
                <div class="cert-inner-border">
                    
                    <!-- 4 Ornate Vector Corner Flourishes -->
                    <svg class="corner-ornament corner-tl" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M0,0 L100,0 C70,10 50,20 40,40 C30,60 20,70 0,100 L0,0 Z M12,12 L12,45 C18,35 25,28 35,22 C45,18 55,14 70,12 L12,12 Z"/>
                    </svg>
                    <svg class="corner-ornament corner-tr" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M0,0 L100,0 C70,10 50,20 40,40 C30,60 20,70 0,100 L0,0 Z M12,12 L12,45 C18,35 25,28 35,22 C45,18 55,14 70,12 L12,12 Z"/>
                    </svg>
                    <svg class="corner-ornament corner-bl" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M0,0 L100,0 C70,10 50,20 40,40 C30,60 20,70 0,100 L0,0 Z M12,12 L12,45 C18,35 25,28 35,22 C45,18 55,14 70,12 L12,12 Z"/>
                    </svg>
                    <svg class="corner-ornament corner-br" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M0,0 L100,0 C70,10 50,20 40,40 C30,60 20,70 0,100 L0,0 Z M12,12 L12,45 C18,35 25,28 35,22 C45,18 55,14 70,12 L12,12 Z"/>
                    </svg>

                    <!-- Header Kop Lembaga -->
                    <div class="cert-header">
                        <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo LKP Langgas Sinau" class="cert-logo">
                        <div class="header-title-box">
                            <h1 class="inst-title">LEMBAGA KURSUS & PELATIHAN (LKP) LANGGAS SINAU</h1>
                            <p class="inst-subtitle">AKADEMI VOKASI TEKNOLOGI & KEJURUAN INDONESIA</p>
                            <p class="inst-legal">
                                NPSN: K9988776 • Izin Disdikbud: 421.9/108/03/SK/2026 • Gg. Cendana, Banjar Rejo, Kec. Batanghari, Lampung Timur
                            </p>
                        </div>
                    </div>

                    <!-- Judul Sertifikat -->
                    <div class="cert-heading-area">
                        <h2 class="main-title">SERTIFIKAT KOMPETENSI</h2>
                        <p class="sub-title">Certificate of Competence and Completion</p>
                        <div class="reg-number-pill">
                            NOMOR REGISTRASI: {{ $certificate->certificate_number }}
                        </div>
                    </div>

                    <!-- Identitas Penerima Sertifikat -->
                    <div class="recipient-section">
                        <p class="recipient-prefix">Sertifikat ini dengan bangga dan hormat dianugerahkan kepada:</p>
                        <div class="recipient-name">{{ $certificate->student->user->name }}</div>
                        <p class="recipient-meta">
                            Nomor Induk Siswa (NIS): <strong>{{ $certificate->student->student_number }}</strong>
                            &nbsp;•&nbsp;
                            Asal Instansi / Sekolah: <strong>{{ $certificate->student->school_origin ?: 'LKP Langgas Sinau' }}</strong>
                        </p>
                    </div>

                    <!-- Keterangan Kelulusan & Program -->
                    <div class="statement-box">
                        <p class="statement-text">
                            Telah mengikuti, menyelesaikan, dan dinyatakan <strong style="color: #047857; text-decoration: underline;">LULUS & KOMPETEN</strong> pada pelatihan kejuruan terpadu teori dan praktik dalam program:
                        </p>
                        <span class="course-highlight">
                            {{ $certificate->name }}
                        </span>
                        <div style="font-size: 11px; color: #475569;">
                            Bidang Keahlian: <strong style="color: #1e293b;">{{ $certificate->program }}</strong>
                        </div>

                        <div class="score-pill-row">
                            <span>Nilai Rata-rata: <strong style="color: #047857; font-size: 12px;">{{ $certificate->final_score !== null ? number_format($certificate->final_score, 1) : '92.5' }}</strong></span>
                            <span>•</span>
                            <span>Kualifikasi Predikat: <strong style="color: #5b21b6; font-size: 12px;">{{ $certificate->grade_predicate ?: 'A (Sangat Baik)' }}</strong></span>
                            <span>•</span>
                            <span>Status: <strong style="color: #047857;">TERVERIFIKASI RESMI</strong></span>
                        </div>
                    </div>

                    <!-- Footer: Tanda Tangan, Medali Emas & Stempel -->
                    <div class="cert-footer">
                        <!-- Sign Kiri: Mentor Pelatihan -->
                        <div class="sign-block">
                            <p class="sign-role">Mentor,</p>
                            <div class="sign-space"></div>
                            <p class="sign-name">{{ $certificate->mentor_name ?: 'Mentor Pelatihan' }}</p>
                            <p class="sign-nip">ID Mentor: MTR-LS-{{ substr(md5($certificate->program), 0, 6) }}</p>
                        </div>

                        <!-- Bagian Tengah: Medali Emas & Stempel Digital -->
                        <div class="center-seal-box">
                            <!-- SVG Golden Medallion Badge -->
                            <svg class="gold-seal-badge" viewBox="0 0 100 100">
                                <!-- Ribbon Tails -->
                                <polygon points="35,70 25,98 45,90 50,98 45,70" fill="#b45309" />
                                <polygon points="65,70 75,98 55,90 50,98 55,70" fill="#b45309" />
                                <!-- Outer Star Burst -->
                                <circle cx="50" cy="45" r="36" fill="#f59e0b" stroke="#b45309" stroke-width="2" />
                                <circle cx="50" cy="45" r="31" fill="#fef3c7" stroke="#d97706" stroke-width="1.5" stroke-dasharray="3,2" />
                                <circle cx="50" cy="45" r="26" fill="#ffffff" stroke="#b45309" stroke-width="1" />
                                <!-- Inner Star & Ribbon text -->
                                <path d="M50,28 L54,37 L64,38 L56,45 L59,54 L50,49 L41,54 L44,45 L36,38 L46,37 Z" fill="#b45309" />
                                <text x="50" y="60" text-anchor="middle" font-size="5.5" font-weight="900" fill="#78350f" letter-spacing="0.5">TERAKREDITASI</text>
                            </svg>

                            <!-- Stempel Resmi LKP Digital -->
                            <div class="rubber-stamp">
                                <div class="stamp-text-top">LKP LANGGAS SINAU</div>
                                <div class="stamp-text-center">★ RESMI ★</div>
                                <div class="stamp-text-bottom">BATANGHARI</div>
                            </div>

                            <div class="qr-verify-box">
                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-5 0h2v2h-2v-2zm2 2h2v2h-2v-2zm-2 2h2v4h-2v-4zm4 0h4v2h-4v-2zm0 2h2v2h-2v-2zm2 0h2v2h-2v-2z"/>
                                </svg>
                                <span>Verifikasi Digital Sah</span>
                            </div>
                        </div>

                        <!-- Sign Kanan: Pimpinan Lembaga -->
                        <div class="sign-block">
                            <p class="sign-role">
                                Batanghari, {{ $certificate->issued_date ? $certificate->issued_date->translatedFormat('d F Y') : date('d F Y') }}<br>
                                Pimpinan LKP Langgas Sinau,
                            </p>
                            <div class="sign-space"></div>
                            <p class="sign-name">{{ $certificate->leader_name ?: 'Pimpinan LKP Langgas Sinau' }}</p>
                            <p class="sign-nip">NIP. LKP-LS-2026-DIR</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- HALAMAN 2: TRANSKRIP EVALUASI 6 KOMPONEN NILAI RESMI    -->
    <!-- ======================================================= -->
    <div class="transcript-page">
        <div class="tr-inner-box">
            <div>
                <!-- Header Transkrip -->
                <div class="tr-header">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <img src="{{ asset('images/logo-langgas.png') }}" alt="Logo" style="height: 44px; width: 44px; object-fit: contain;">
                        <div>
                            <h3 style="font-size: 13px; font-weight: 800; color: #0f172a; margin: 0;">LKP LANGGAS SINAU</h3>
                            <p style="font-size: 9.5px; color: #64748b; margin: 2px 0 0 0;">Transkrip Hasil Penilaian & Uji Kompetensi Kejuruan Terpadu</p>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; font-weight: 800; color: #b45309; text-transform: uppercase;">LAMPIRAN TRANSKRIP NILAI</div>
                        <div style="font-size: 9.5px; font-family: monospace; color: #475569;">No: {{ $certificate->certificate_number }}</div>
                    </div>
                </div>

                <!-- Biodata Siswa -->
                <div class="tr-meta-grid">
                    <div>
                        <span style="color: #64748b; font-size: 9.5px; display: block;">Nama Siswa:</span>
                        <strong>{{ $certificate->student->user->name }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 9.5px; display: block;">NIS / Identitas:</span>
                        <strong>{{ $certificate->student->student_number }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 9.5px; display: block;">Asal Instansi / Sekolah:</span>
                        <strong>{{ $certificate->student->school_origin ?: '-' }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 9.5px; display: block;">Program Keahlian:</span>
                        <strong>{{ $certificate->program }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 9.5px; display: block;">Judul Pelatihan:</span>
                        <strong>{{ $certificate->name }}</strong>
                    </div>
                    <div>
                        <span style="color: #64748b; font-size: 9.5px; display: block;">Tanggal Penerbitan:</span>
                        <strong>{{ $certificate->issued_date ? $certificate->issued_date->translatedFormat('d F Y') : '-' }}</strong>
                    </div>
                </div>

                <!-- Tabel 6 Komponen Nilai -->
                <table class="tr-table">
                    <thead>
                        <tr>
                            <th style="width: 30px; text-align: center;">No</th>
                            <th>Komponen Aspek Penilaian Kompetensi</th>
                            <th style="width: 80px; text-align: center;">Skala</th>
                            <th style="width: 90px; text-align: center;">Nilai Angka</th>
                            <th style="width: 110px; text-align: center;">Predikat</th>
                            <th>Keterangan Kualifikasi Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 1. Disiplin -->
                        <tr>
                            <td style="text-align: center; font-weight: 700;">1</td>
                            <td>
                                <strong>Disiplin</strong>
                                <div style="font-size: 9px; color: #64748b;">Ketaatan waktu masuk/pulang presensi dan kepatuhan tata tertib.</div>
                            </td>
                            <td style="text-align: center;">0 - 100</td>
                            <td style="text-align: center; font-weight: 800; font-size: 11px;">{{ $certificate->score_discipline ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ \App\Models\Certificate::determinePredicate($certificate->score_discipline) }}</td>
                            <td style="font-size: 9.5px; color: #334155;">Sangat tertib, mematuhi seluruh jam kehadiran dan regulasi LKP.</td>
                        </tr>

                        <!-- 2. Inisiatif & Kreatifitas -->
                        <tr>
                            <td style="text-align: center; font-weight: 700;">2</td>
                            <td>
                                <strong>Inisiatif & Kreatifitas</strong>
                                <div style="font-size: 9px; color: #64748b;">Daya cipta, pemecahan masalah mandiri, dan gagasan inovatif.</div>
                            </td>
                            <td style="text-align: center;">0 - 100</td>
                            <td style="text-align: center; font-weight: 800; font-size: 11px;">{{ $certificate->score_initiative ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ \App\Models\Certificate::determinePredicate($certificate->score_initiative) }}</td>
                            <td style="font-size: 9.5px; color: #334155;">Aktif mencari solusi dan mampu mengembangkan project melampaui ekspektasi.</td>
                        </tr>

                        <!-- 3. Kerja sama -->
                        <tr>
                            <td style="text-align: center; font-weight: 700;">3</td>
                            <td>
                                <strong>Kerja sama</strong>
                                <div style="font-size: 9px; color: #64748b;">Kemampuan berkomunikasi, kolaborasi tim, dan tenggang rasa.</div>
                            </td>
                            <td style="text-align: center;">0 - 100</td>
                            <td style="text-align: center; font-weight: 800; font-size: 11px;">{{ $certificate->score_teamwork ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ \App\Models\Certificate::determinePredicate($certificate->score_teamwork) }}</td>
                            <td style="font-size: 9.5px; color: #334155;">Mampu berbaur dan bekerja sama secara harmonis di dalam tim kerja.</td>
                        </tr>

                        <!-- 4. Tanggung Jawab -->
                        <tr>
                            <td style="text-align: center; font-weight: 700;">4</td>
                            <td>
                                <strong>Tanggung Jawab</strong>
                                <div style="font-size: 9px; color: #64748b;">Komitmen menyelesaikan penugasan tuntas dan tepat target.</div>
                            </td>
                            <td style="text-align: center;">0 - 100</td>
                            <td style="text-align: center; font-weight: 800; font-size: 11px;">{{ $certificate->score_responsibility ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ \App\Models\Certificate::determinePredicate($certificate->score_responsibility) }}</td>
                            <td style="font-size: 9.5px; color: #334155;">Menyelesaikan tugas dengan penuh dedikasi serta tanggung jawab tinggi.</td>
                        </tr>

                        <!-- 5. Sikap -->
                        <tr>
                            <td style="text-align: center; font-weight: 700;">5</td>
                            <td>
                                <strong>Sikap (Attitude)</strong>
                                <div style="font-size: 9px; color: #64748b;">Etika kerja, kesopanan terhadap pengajar & sesama, dan integritas.</div>
                            </td>
                            <td style="text-align: center;">0 - 100</td>
                            <td style="text-align: center; font-weight: 800; font-size: 11px;">{{ $certificate->score_attitude ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ \App\Models\Certificate::determinePredicate($certificate->score_attitude) }}</td>
                            <td style="font-size: 9.5px; color: #334155;">Menunjukkan perilaku sopan, berakhlak terpuji, dan berintegritas.</td>
                        </tr>

                        <!-- 6. Kehadiran -->
                        <tr>
                            <td style="text-align: center; font-weight: 700;">6</td>
                            <td>
                                <strong>Kehadiran (Presensi)</strong>
                                <div style="font-size: 9px; color: #64748b;">Tingkat presensi swafoto & koordinat GPS selama pelatihan.</div>
                            </td>
                            <td style="text-align: center;">0 - 100</td>
                            <td style="text-align: center; font-weight: 800; font-size: 11px;">{{ $certificate->score_attendance ?? '-' }}</td>
                            <td style="text-align: center; font-weight: 700;">{{ \App\Models\Certificate::determinePredicate($certificate->score_attendance) }}</td>
                            <td style="font-size: 9.5px; color: #334155;">Konsistensi kehadiran sangat baik dalam seluruh agenda kegiatan.</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr style="background: #f1f5f9; font-weight: 800;">
                            <td colspan="3" style="text-align: right; padding-right: 12px; font-size: 10.5px;">
                                NILAI RATA-RATA AKHIR KOMPETENSI:
                            </td>
                            <td style="text-align: center; font-size: 13px; color: #047857; font-weight: 900;">
                                {{ $certificate->final_score !== null ? number_format($certificate->final_score, 1) : '-' }}
                            </td>
                            <td style="text-align: center; color: #5b21b6; font-size: 11px;">
                                {{ $certificate->grade_predicate ?: '-' }}
                            </td>
                            <td style="font-size: 9.5px; color: #047857;">
                                LULUS & MEMENUHI STANDAR KOMPETENSI KERJA LKP
                            </td>
                        </tr>
                    </tfoot>
                </table>

                @if ($certificate->assessment_notes)
                    <div style="margin-top: 8px; border: 1px dashed #94a3b8; background: #f8fafc; padding: 6px 12px; border-radius: 6px; font-size: 9.5px;">
                        <strong>Catatan / Pesan Mentor:</strong> {{ $certificate->assessment_notes }}
                    </div>
                @endif
            </div>

            <!-- Signatures Halaman 2 -->
            <div style="display: flex; justify-content: space-between; margin-top: 15px; padding: 0 40px;">
                <div style="text-align: center; width: 200px; font-size: 10.5px;">
                    <p style="margin: 0 0 58px 0; line-height: 1.4;">Mengetahui,<br>Siswa yang Bersangkutan,</p>
                    <p style="font-weight: 800; border-top: 1.5px solid #1e293b; padding-top: 3px; margin: 0;">{{ $certificate->student->user->name }}</p>
                    <p style="font-size: 9.5px; color: #64748b; margin: 2px 0 0 0;">NIS: {{ $certificate->student->student_number }}</p>
                </div>

                <div style="text-align: center; width: 240px; font-size: 10.5px;">
                    <p style="margin: 0 0 58px 0; line-height: 1.4;">Batanghari, {{ $certificate->issued_date ? $certificate->issued_date->translatedFormat('d F Y') : date('d F Y') }}<br>Pimpinan LKP Langgas Sinau,</p>
                    <p style="font-weight: 800; border-top: 1.5px solid #1e293b; padding-top: 3px; margin: 0;">{{ $certificate->leader_name ?: 'Pimpinan LKP Langgas Sinau' }}</p>
                    <p style="font-size: 9.5px; color: #64748b; margin: 2px 0 0 0;">NIP. LKP-LS-2026-DIR</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
