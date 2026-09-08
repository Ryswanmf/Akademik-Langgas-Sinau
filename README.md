# Sistem Informasi Akademik & Presensi LKP Langgas Sinau

> "Berdikari Mengenal Diri"

Platform manajemen akademik terintegrasi untuk Lembaga Kursus dan Pelatihan (LKP) Langgas Sinau. Sistem ini menyediakan pencatatan presensi digital berbasis swafoto wajah dan geolokasi GPS, rekapitulasi kehadiran, pengajuan perizinan, evaluasi 6 komponen penilaian kompetensi, serta penerbitan dan pencetakan sertifikat resmi kelulusan.

---

## Teknologi dan Stack

<p align="left">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=black" alt="Alpine.js" />
  <img src="https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite" />
  <img src="https://img.shields.io/badge/MySQL-Compatible-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  <img src="https://img.shields.io/badge/SQLite-Supported-07405E?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite" />
</p>

---

## Fitur Utama

### 1. Presensi Digital Siswa (Mobile First)
* Swafoto Wajah (Selfie): Perekaman video live kamera depan via HTML5 MediaDevices dengan panduan oval wajah, serta fallback upload file kamera HP.
* Geolokasi GPS Ketat: Validasi koordinat posisi siswa terhadap titik acuan resmi LKP Langgas Sinau (Gg. Cendana, Banjar Rejo, Batanghari, Lampung Timur) dengan batas radius toleransi 150 meter.
* Validasi Waktu Presensi:
  * 08.00 - 09.30 WIB : Presensi Masuk (Hadir Tepat Waktu).
  * 09.31 - 13.50 WIB : Presensi Masuk (Terlambat).
  * 14.00 - 17.00 WIB : Presensi Pulang (Hadir Jam Keluar).
  * Di atas 17.00 WIB : Presensi Pulang dicatat sebagai Jam Keluar (Lembur).
* Evaluasi Auto-Alpa: Siswa aktif yang tidak melakukan presensi hingga pukul 17.00 WIB otomatis ditandai Alpa oleh sistem scheduler harian atau melalui tombol eksekusi admin.
* Hari Libur Otomatis: Pengecualian hari Minggu sebagai hari libur pelatihan.

### 2. Rekapitulasi & Cetak Hasil Presensi
* Rekapitulasi per siswa dengan persentase disiplin kehadiran (Hadir, Terlambat, Izin, Sakit, Alpa).
* Cetak dokumen rekap absensi individual siap cetak format A4 portrait, memuat detail jam masuk, jam keluar, jarak koordinat, dan bukti foto selfie.

### 3. Penilaian Kompetensi & Penerbitan Sertifikat
* 6 Aspek Evaluasi Kompetensi:
  1. Disiplin Kerja
  2. Inisiatif dan Kreativitas
  3. Kerja Sama
  4. Tanggung Jawab
  5. Sikap dan Perilaku
  6. Kehadiran
* Kustomisasi Dokumen:
  * Fleksibilitas nama kelas / program pelatihan (input teks mandiri).
  * Fleksibilitas nama Mentor / Pembimbing per sertifikat.
  * Fleksibilitas nama Pimpinan Lembaga per sertifikat.
* Cetak Sertifikat Resmi (A4 Landscape): Desain elegan berbingkai emas, nomor register sertifikat, QR code verifikasi, serta area tanda tangan dan stempel yang proporsional.
* Cetak Transkrip Nilai (A4 Portrait): Rekapitulasi angka nilai, predikat mutu, dan skala kompetensi.
* Status Publikasi: Pengendalian status Draft dan Published untuk memastikan dokumen hanya dapat dilihat siswa setelah disetujui admin.

### 4. Perizinan Siswa
* Pengajuan izin sakit dan keperluan khusus secara mandiri oleh siswa disertai upload bukti surat/dokumen pendukung.
* Approval workflow oleh administrator (Disetujui / Ditolak).

### 5. Tampilan Responsif & Identitas Lembaga
* Responsive Mobile Drawer: Navigasi samping lipat dengan tombol hamburger pada perangkat smartphone dan tablet.
* Tabel Aman: Penerapan scrollbar horizontal internal pada seluruh tabel data untuk mencegah pergeseran tampilan pada layar sempit.
* Paket Favicon Komprehensif: Dukungan multi-resolusi (.ico, PNG 16x16, 32x32, Apple Touch Icon 180x180, dan Android Chrome PWA Webmanifest).

---

## Skema Aturan Presensi Harian

| Rentang Waktu | Jenis Presensi | Status / Keterangan Sistem |
|---|---|---|
| 08.00 - 09.30 WIB | Presensi Masuk | Hadir (Tepat Waktu) |
| 09.31 - 13.50 WIB | Presensi Masuk | Hadir Terlambat |
| 13.51 - 17.00 WIB | Presensi Masuk | Ditolak (Batas Masuk Berakhir) |
| 14.00 - 17.00 WIB | Presensi Pulang | Hadir Jam Keluar (Resmi) |
| > 17.00 WIB | Presensi Pulang | Jam Keluar (Lembur) |
| > 17.00 WIB | Evaluasi Akhir Hari | Alpa Otomatis (Jika Tanpa Presensi) |

---

## Kebutuhan Sistem

* PHP >= 8.2 (Ekstensi: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, GD)
* Composer >= 2.x
* Node.js >= 18.x & NPM
* Web Server (Apache, Nginx, LiteSpeed, atau PHP Built-in Server)
* Database (MySQL 8.x / MariaDB 10.x atau SQLite 3.x)

---

## Panduan Instalasi Lokal

### 1. Unduh dan Masuk ke Direktori Proyek
```bash
cd D:/laragon/www/Akademi-Langgas-Sinau
```

### 2. Instalasi Dependensi Backend & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (.env)
Salin berkas konfigurasi sampel jika belum tersedia:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi koneksi database dan koordinat institusi pada `.env`:
```env
APP_NAME="Akademi Langgas Sinau"
APP_ENV=local
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta

LKP_NAME="LKP Langgas Sinau Akademi"
LKP_ADDRESS="Gg. Cendana, Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung 34181"
LKP_LATITUDE=-5.124000
LKP_LONGITUDE=105.337000
LKP_RADIUS_METERS=150
LKP_STRICT_RADIUS=true
```

### 4. Migrasi Database dan Storage Link
```bash
php artisan migrate
php artisan storage:link
```

### 5. Kompilasi Aset Frontend
```bash
npm run build
```

### 6. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui browser: `http://127.0.0.1:8000`

---

## Panduan Penerapan di Server Produksi (Hosting / VPS)

### 1. Pengaturan HTTPS (SSL)
Fitur kamera dan sensor GPS wajib berjalan di bawah protokol HTTPS yang aman.
* Konfigurasi di berkas `.env` server:
  ```env
  APP_ENV=production
  APP_DEBUG=false
  APP_URL=https://nama-domain-anda.com
  APP_FORCE_HTTPS=true
  ```
* Aktifkan sertifikat SSL (Let's Encrypt / AutoSSL / Cloudflare).
* Berkas `public/.htaccess` telah dikonfigurasi untuk mengalihkan seluruh lalu lintas HTTP ke HTTPS secara otomatis pada lingkungan produksi.

### 2. Pemasangan Cron Job Scheduler
Tambahkan entri cron job berikut pada crontab server atau cPanel Task Scheduler (dijalankan setiap menit):
```bash
* * * * * /usr/local/bin/php /path-to-project/artisan schedule:run >> /dev/null 2>&1
```

Perintah di atas akan secara otomatis memicu tugas Auto-Alpa harian pada pukul 17.01 WIB untuk hari Senin sampai dengan Sabtu.

---

## Pengujian Otomatis

Aplikasi ini dilengkapi pengujian fungsional terautomasi untuk memvalidasi alur autentikasi, presensi swafoto & GPS, kalkulasi jarak, dan hak akses.

Jalankan pengujian melalui terminal:
```bash
php artisan test
```

---

## Struktur Direktori Utama

```text
app/
|-- Console/Commands/AutoAlpaCommand.php   # Perintah scheduler otomatis alpa
|-- Http/Controllers/
|   |-- Admin/                            # Manajemen data siswa, presensi, kelas, nilai, sertifikat
|   `-- Siswa/                            # Modul presensi mandiri, pengajuan izin, profil, nilai
|-- Models/                               # Attendance, Certificate, Student, TrainingClass, Permission
config/
`-- attendance.php                        # Konfigurasi titik koordinat, radius GPS, dan jam operasional
resources/
|-- views/
|   |-- admin/                            # Tampilan antarmuka panel admin dan cetak dokumen
|   |-- siswa/                            # Tampilan antarmuka mandiri siswa
|   |-- components/                       # Komponen navbar, sidebar mobile, alert
|   `-- layouts/                          # Layout master admin, siswa, dan public
public/
|-- uploads/attendances/                  # Direktori berkas swafoto kehadiran siswa
`-- images/                               # Berkas logo resmi institusi
```

---

## Lisensi

Hak cipta dilindungi undang-undang. Sistem ini dikembangkan untuk operasional internal **LKP Langgas Sinau**.
