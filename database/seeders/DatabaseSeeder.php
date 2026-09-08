<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Grade;
use App\Models\Certificate;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Demo
        $admin = User::create([
            'name' => 'Administrator Langgas',
            'email' => 'admin@langgas-sinau.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Data Kelas
        $kelasWeb = Classes::create([
            'name' => 'Web Development Fullstack',
            'program' => 'Teknologi Informasi',
            'description' => 'Kelas intensif pembuatan aplikasi web modern menggunakan Laravel, Tailwind, dan MySQL.',
            'status' => 'aktif',
        ]);

        $kelasDesign = Classes::create([
            'name' => 'Desain Grafis & UI/UX',
            'program' => 'Desain Komunikasi Visual',
            'description' => 'Kelas perancangan visual, user experience, branding, dan prototyping antarmuka.',
            'status' => 'aktif',
        ]);

        $kelasOffice = Classes::create([
            'name' => 'Administrasi Perkantoran & Digital Office',
            'program' => 'Bisnis & Manajemen',
            'description' => 'Kelas keahlian tata kelola dokumen modern, spreadsheet tingkat lanjut, dan pembukuan.',
            'status' => 'aktif',
        ]);

        // 3. Akun Siswa Demo (Budi Pratama)
        $userSiswaDemo = User::create([
            'name' => 'Budi Pratama',
            'email' => 'siswa@langgas-sinau.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        $studentDemo = Student::create([
            'user_id' => $userSiswaDemo->id,
            'class_id' => $kelasWeb->id,
            'student_number' => 'LS-2026-001',
            'phone' => '081234567890',
            'address' => 'Jl. Malioboro No. 45, Yogyakarta',
            'photo' => null,
            'program' => 'Teknologi Informasi',
            'school_origin' => 'SMK Negeri 1 Batanghari',
            'entry_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        // 9 Siswa lainnya (Total 10 siswa)
        $dummyStudentsData = [
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@langgas-sinau.com',
                'class_id' => $kelasWeb->id,
                'student_number' => 'LS-2026-002',
                'phone' => '081234567802',
                'address' => 'Jl. Gejayan No. 18, Sleman, DIY',
                'program' => 'Teknologi Informasi',
                'entry_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Rian Hidayat',
                'email' => 'rian@langgas-sinau.com',
                'class_id' => $kelasWeb->id,
                'student_number' => 'LS-2026-003',
                'phone' => '081234567803',
                'address' => 'Jl. Kaliurang Km 5, Sleman',
                'program' => 'Teknologi Informasi',
                'entry_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Annisa Putri',
                'email' => 'annisa@langgas-sinau.com',
                'class_id' => $kelasWeb->id,
                'student_number' => 'LS-2026-004',
                'phone' => '081234567804',
                'address' => 'Jl. Bantul No. 89, Bantul',
                'program' => 'Teknologi Informasi',
                'entry_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Dimas Wicaksono',
                'email' => 'dimas@langgas-sinau.com',
                'class_id' => $kelasDesign->id,
                'student_number' => 'LS-2026-005',
                'phone' => '081234567805',
                'address' => 'Jl. Kusumanegara No. 102, Yogyakarta',
                'program' => 'Desain Komunikasi Visual',
                'entry_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Farah Salsabila',
                'email' => 'farah@langgas-sinau.com',
                'class_id' => $kelasDesign->id,
                'student_number' => 'LS-2026-006',
                'phone' => '081234567806',
                'address' => 'Jl. Palagan No. 55, Sleman',
                'program' => 'Desain Komunikasi Visual',
                'entry_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Gilang Ramadhan',
                'email' => 'gilang@langgas-sinau.com',
                'class_id' => $kelasDesign->id,
                'student_number' => 'LS-2026-007',
                'phone' => '081234567807',
                'address' => 'Jl. Ring Road Utara, Yogyakarta',
                'program' => 'Desain Komunikasi Visual',
                'entry_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Maya Indah',
                'email' => 'maya@langgas-sinau.com',
                'class_id' => $kelasOffice->id,
                'student_number' => 'LS-2026-008',
                'phone' => '081234567808',
                'address' => 'Jl. Solo Km 9, Sleman',
                'program' => 'Bisnis & Manajemen',
                'entry_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Bagas Saputra',
                'email' => 'bagas@langgas-sinau.com',
                'class_id' => $kelasOffice->id,
                'student_number' => 'LS-2026-009',
                'phone' => '081234567809',
                'address' => 'Jl. Wates Km 3, Kasihan, Bantul',
                'program' => 'Bisnis & Manajemen',
                'entry_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'status' => 'aktif',
            ],
            [
                'name' => 'Rina Kartika',
                'email' => 'rina@langgas-sinau.com',
                'class_id' => $kelasOffice->id,
                'student_number' => 'LS-2026-010',
                'phone' => '081234567810',
                'address' => 'Jl. Parangtritis No. 71, Yogyakarta',
                'program' => 'Bisnis & Manajemen',
                'entry_date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
                'status' => 'aktif',
            ],
        ];

        $allStudents = [$studentDemo];
        foreach ($dummyStudentsData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'class_id' => $data['class_id'],
                'student_number' => $data['student_number'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'photo' => null,
                'program' => $data['program'],
                'school_origin' => $data['school_origin'] ?? 'SMK Negeri 1 Batanghari',
                'entry_date' => $data['entry_date'],
                'status' => $data['status'],
            ]);

            $allStudents[] = $student;
        }

        // 4. Jadwal Pelatihan (Schedules)
        $schedules = [
            Schedule::create([
                'class_id' => $kelasWeb->id,
                'title' => 'Pengenalan HTML5 & CSS3 Modern',
                'date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'room' => 'Lab Komputer 1',
                'description' => 'Dasar sintaks HTML5 semantik dan styling CSS modern.',
            ]),
            Schedule::create([
                'class_id' => $kelasWeb->id,
                'title' => 'Dasar PHP Modern & OOP',
                'date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'room' => 'Lab Komputer 1',
                'description' => 'Pemrograman berbasis objek dan modularitas PHP.',
            ]),
            Schedule::create([
                'class_id' => $kelasWeb->id,
                'title' => 'Framework Laravel 12 & MVC Architecture',
                'date' => Carbon::today()->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'room' => 'Lab Komputer 1',
                'description' => 'Routing, Controller, Model Eloquent, dan Blade Templating.',
            ]),
            Schedule::create([
                'class_id' => $kelasWeb->id,
                'title' => 'Integrasi Tailwind CSS & Alpine.js',
                'date' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'room' => 'Lab Komputer 1',
                'description' => 'Desain interaktif responsif tanpa framework berat.',
            ]),
            Schedule::create([
                'class_id' => $kelasDesign->id,
                'title' => 'Fundamental Typography & Color Harmony',
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'room' => 'Studio Desain A',
                'description' => 'Memahami komposisi visual, psikologi warna, dan hierarki tipografi.',
            ]),
            Schedule::create([
                'class_id' => $kelasDesign->id,
                'title' => 'Figma Prototyping & Design System',
                'date' => Carbon::today()->format('Y-m-d'),
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
                'room' => 'Studio Desain A',
                'description' => 'Membuat wireframe, komponen UI interaktif dan prototype.',
            ]),
            Schedule::create([
                'class_id' => $kelasOffice->id,
                'title' => 'Automasi Spreadsheet & Formula Finansial',
                'date' => Carbon::today()->format('Y-m-d'),
                'start_time' => '08:30:00',
                'end_time' => '11:30:00',
                'room' => 'Ruang Teori B',
                'description' => 'Kombinasi VLOOKUP, XLOOKUP, Pivot Table, dan Dashboard ringkas.',
            ]),
        ];

        // 5. Data Absensi (Attendances)
        // Web class students: demo, siti, rian, annisa
        $webStudents = array_slice($allStudents, 0, 4);
        foreach ($webStudents as $index => $std) {
            // Pertemuan 1
            Attendance::create([
                'student_id' => $std->id,
                'schedule_id' => $schedules[0]->id,
                'date' => $schedules[0]->date,
                'check_in_time' => '07:55:00',
                'check_out_time' => '16:05:00',
                'check_in_lat' => -7.795620,
                'check_in_lng' => 110.369510,
                'check_in_distance' => 24, // 24 meter dari LKP
                'check_out_lat' => -7.795615,
                'check_out_lng' => 110.369520,
                'check_out_distance' => 28,
                'status' => 'hadir',
                'note' => 'Hadir tepat waktu dan aktif dalam sesi praktik.',
            ]);

            // Pertemuan 2
            $status = ($index === 1) ? 'izin' : 'hadir';
            Attendance::create([
                'student_id' => $std->id,
                'schedule_id' => $schedules[1]->id,
                'date' => $schedules[1]->date,
                'check_in_time' => ($status === 'hadir') ? '08:05:00' : null,
                'check_out_time' => ($status === 'hadir') ? '16:00:00' : null,
                'check_in_lat' => ($status === 'hadir') ? -7.795650 : null,
                'check_in_lng' => ($status === 'hadir') ? 110.369480 : null,
                'check_in_distance' => ($status === 'hadir') ? 42 : null,
                'status' => $status,
                'note' => ($status === 'izin') ? 'Izin keperluan keluarga mendesak' : 'Hadir tepat waktu.',
            ]);

            // Pertemuan Hari ini (Untuk demo student index 0, dibiarkan belum presensi agar siap dicoba)
            $todayStatus = ($index === 2) ? 'sakit' : 'hadir';
            if ($index !== 0) {
                Attendance::create([
                    'student_id' => $std->id,
                    'schedule_id' => $schedules[2]->id,
                    'date' => $schedules[2]->date,
                    'check_in_time' => ($todayStatus === 'hadir') ? '07:50:00' : null,
                    'check_out_time' => ($todayStatus === 'hadir' && $index === 1) ? '16:10:00' : null,
                    'check_in_lat' => ($todayStatus === 'hadir') ? -7.795590 : null,
                    'check_in_lng' => ($todayStatus === 'hadir') ? 110.369530 : null,
                    'check_in_distance' => ($todayStatus === 'hadir') ? 35 : null,
                    'status' => $todayStatus,
                    'note' => ($todayStatus === 'sakit') ? 'Surat dokter terlampir' : 'Hadir di lab.',
                ]);
            }
        }

        // Siswa desain
        $designStudents = array_slice($allStudents, 4, 3);
        foreach ($designStudents as $index => $std) {
            Attendance::create([
                'student_id' => $std->id,
                'schedule_id' => $schedules[4]->id,
                'date' => $schedules[4]->date,
                'status' => ($index === 2) ? 'alpa' : 'hadir',
                'note' => ($index === 2) ? 'Tanpa keterangan' : 'Hadir dan menyelesaikan tugas',
            ]);
            Attendance::create([
                'student_id' => $std->id,
                'schedule_id' => $schedules[5]->id,
                'date' => $schedules[5]->date,
                'status' => 'hadir',
                'note' => 'Hadir tepat waktu.',
            ]);
        }

        // Siswa office
        $officeStudents = array_slice($allStudents, 7, 3);
        foreach ($officeStudents as $std) {
            Attendance::create([
                'student_id' => $std->id,
                'schedule_id' => $schedules[6]->id,
                'date' => $schedules[6]->date,
                'status' => 'hadir',
                'note' => 'Hadir tepat waktu.',
            ]);
        }

        // 6. Data Perizinan (Permissions)
        Permission::create([
            'student_id' => $studentDemo->id,
            'date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'type' => 'izin',
            'reason' => 'Mengikuti kegiatan seminar nasional teknologi informasi di universitas.',
            'evidence' => 'evidence/sample-surat-dokter.pdf',
            'status' => 'menunggu',
            'admin_note' => null,
        ]);

        Permission::create([
            'student_id' => $allStudents[1]->id, // Siti
            'date' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'type' => 'izin',
            'reason' => 'Keperluan keluarga penting di luar kota.',
            'evidence' => 'evidence/sample-surat-dokter.pdf',
            'status' => 'disetujui',
            'admin_note' => 'Disetujui. Harap mempelajari materi modul 2 yang terlewat.',
        ]);

        Permission::create([
            'student_id' => $allStudents[2]->id, // Rian
            'date' => Carbon::today()->format('Y-m-d'),
            'type' => 'sakit',
            'reason' => 'Demam dan flu, istirahat atas petunjuk dokter.',
            'evidence' => 'evidence/sample-surat-dokter.pdf',
            'status' => 'disetujui',
            'admin_note' => 'Semoga lekas sembuh.',
        ]);

        // 7. Data Nilai (Grades)
        Grade::create([
            'student_id' => $studentDemo->id,
            'class_id' => $kelasWeb->id,
            'subject' => 'HTML5 & Responsive CSS',
            'score' => 90.00,
            'description' => 'Sangat menguasai konsep flexbox, grid, dan semantic markup.',
        ]);

        Grade::create([
            'student_id' => $studentDemo->id,
            'class_id' => $kelasWeb->id,
            'subject' => 'Pemrograman PHP Dasar & Database MySQL',
            'score' => 88.50,
            'description' => 'Mampu merancang relasi tabel dan query Eloquent dengan baik.',
        ]);

        Grade::create([
            'student_id' => $studentDemo->id,
            'class_id' => $kelasWeb->id,
            'subject' => 'Arsitektur Laravel MVC',
            'score' => 92.00,
            'description' => 'Implementasi Clean Code dan pemanfaatan Blade yang sangat rapi.',
        ]);

        foreach (array_slice($allStudents, 1, 3) as $std) {
            Grade::create([
                'student_id' => $std->id,
                'class_id' => $kelasWeb->id,
                'subject' => 'HTML5 & Responsive CSS',
                'score' => rand(80, 95),
                'description' => 'Penyelesaian proyek mini web statis sangat memuaskan.',
            ]);
        }

        // 8. Data Nilai & Sertifikat (Certificates)
        Certificate::create([
            'student_id' => $studentDemo->id,
            'name' => 'Sertifikat Kompetensi Pemrograman Web Dasar',
            'certificate_number' => 'LS-CERT-2026-001',
            'program' => 'Teknologi Informasi',
            'issued_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'file' => 'certificates/sample-certificate.pdf',
            'is_published' => true,
            'score_discipline' => 92,
            'score_initiative' => 90,
            'score_teamwork' => 88,
            'score_responsibility' => 95,
            'score_attitude' => 96,
            'score_attendance' => 94,
            'final_score' => 92.50,
            'grade_predicate' => 'A (Sangat Baik)',
            'assessment_notes' => 'Menunjukkan kedisiplinan luar biasa, aktif berinisiatif, dan mampu berkolaborasi tim secara profesional.',
        ]);

        Certificate::create([
            'student_id' => $allStudents[1]->id,
            'name' => 'Sertifikat Kelulusan HTML & CSS Fundamental',
            'certificate_number' => 'LS-CERT-2026-002',
            'program' => 'Teknologi Informasi',
            'issued_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'file' => 'certificates/sample-certificate.pdf',
            'is_published' => false,
            'score_discipline' => 85,
            'score_initiative' => 80,
            'score_teamwork' => 82,
            'score_responsibility' => 88,
            'score_attitude' => 90,
            'score_attendance' => 85,
            'final_score' => 85.00,
            'grade_predicate' => 'A (Sangat Baik)',
            'assessment_notes' => 'Draft evaluasi kompetensi internal instruktur.',
        ]);

        // 9. Data Pengumuman (Announcements)
        Announcement::create([
            'title' => 'Jadwal Ujian Praktik Tengah Semester Genap 2026',
            'content' => "Diberitahukan kepada seluruh siswa Akademi Langgas Sinau bahwa ujian praktik akan dilaksanakan pada tanggal 20 s/d 25 bulan ini. Harap seluruh siswa mempersiapkan portofolio mini project masing-masing dan memastikan tingkat kehadiran di atas 80%.\n\nJika ada kendala teknis atau perizinan, segera koordinasikan dengan bagian akademik.",
            'image' => null,
            'published_at' => Carbon::now()->subDays(2),
            'status' => 'published',
        ]);

        Announcement::create([
            'title' => 'Workshop Eksklusif: Membangun Karir di Industri Digital & Freelance',
            'content' => "Akademi Langgas Sinau menghadirkan praktisi industri digital dalam sesi mentoring 'Berdikari Mengenal Diri: Menembus Pasar Kerja Global'. Workshop ini gratis untuk seluruh siswa aktif angkatan 2026 pada hari Sabtu mendatang pukul 09.00 WIB.",
            'image' => null,
            'published_at' => Carbon::now()->subDays(1),
            'status' => 'published',
        ]);

        Announcement::create([
            'title' => 'Informasi Pemeliharaan Server Lab Komputer',
            'content' => "Pemeliharaan rutin jaringan dan server lab akan dilaksanakan pada akhir pekan ini. Akses internet lab lokal sementara dinonaktifkan mulai Sabtu malam pukul 22.00 WIB.",
            'image' => null,
            'published_at' => Carbon::now(),
            'status' => 'published',
        ]);
    }
}
