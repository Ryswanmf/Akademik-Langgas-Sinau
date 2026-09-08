<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicAppTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Langgas Sinau Akademi', false);
        $response->assertSee('Berdikari Mengenal Diri', false);
        $response->assertSee('logo-langgas.png', false);
        $response->assertSee('wa.me', false);
        $response->assertSee('instagram.com', false);
        $response->assertSee('tiktok.com', false);
        $response->assertSee('facebook.com', false);
        $response->assertSee('Lupa Sandi?', false);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $responseSiswa = $this->get('/siswa/dashboard');
        $responseSiswa->assertRedirect('/login');
    }

    public function test_admin_can_login_and_access_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@langgas-sinau.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');

        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $dashResponse = $this->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Selamat Datang di Portal Admin');
        $dashResponse->assertSee('logo-langgas.png');
    }

    public function test_siswa_can_login_and_access_siswa_dashboard(): void
    {
        $response = $this->post('/login', [
            'email' => 'siswa@langgas-sinau.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/siswa/dashboard');

        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $dashResponse = $this->get('/siswa/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Statistik Kehadiran Saya');
    }

    public function test_siswa_cannot_access_admin_routes(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/siswa/dashboard');
    }

    public function test_admin_cannot_access_siswa_routes(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $response = $this->get('/siswa/dashboard');
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_admin_can_access_all_modules(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $routes = [
            '/admin/dashboard',
            '/admin/students',
            '/admin/classes',
            '/admin/schedules',
            '/admin/attendances',
            '/admin/attendances/rekap',
            '/admin/permissions',
            '/admin/grades',
            '/admin/certificates',
            '/admin/announcements',
        ];

        foreach ($routes as $route) {
            $res = $this->get($route);
            $res->assertStatus(200);
        }
    }

    public function test_admin_can_view_student_attendance_print_sheet(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $student = Student::first();
        $response = $this->get("/admin/attendances/print/{$student->id}");
        $response->assertStatus(200);
        $response->assertSee('LEMBAR REKAPITULASI HASIL ABSENSI SISWA');
        $response->assertSee('LKP LANGGAS SINAU');
        $response->assertSee($student->student_number);
    }

    public function test_siswa_can_access_all_siswa_modules(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $routes = [
            '/siswa/dashboard',
            '/siswa/profile',
            '/siswa/schedules',
            '/siswa/attendances',
            '/siswa/permissions',
            '/siswa/grades',
            '/siswa/certificates',
            '/siswa/announcements',
        ];

        foreach ($routes as $route) {
            $res = $this->get($route);
            $res->assertStatus(200);
        }
    }

    public function test_application_timezone_is_asia_jakarta(): void
    {
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));
    }

    public function test_lkp_location_is_configured_to_banjar_rejo_lampung_timur(): void
    {
        $this->assertEquals(-5.1240, config('attendance.latitude'));
        $this->assertEquals(105.3370, config('attendance.longitude'));
        $this->assertStringContainsString('Banjar Rejo', config('attendance.address'));
        $this->assertStringContainsString('Batanghari', config('attendance.address'));
        $this->assertStringContainsString('Lampung Timur', config('attendance.address'));
    }

    public function test_siswa_can_clock_in_and_clock_out_with_selfie_and_coords(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        // Dummy 1x1 transparent GIF base64 data url
        $fakeBase64Photo = 'data:image/jpeg;base64,' . base64_encode('fake-jpeg-binary-data');

        // Set time within on-time window (08:30 WIB)
        Carbon::setTestNow(Carbon::today()->setTime(8, 30, 0));

        // 1. Test Clock-In at Banjar Rejo coordinates (~1.5 meters from LKP)
        $clockInResponse = $this->post('/siswa/attendances/clock-in', [
            'latitude' => -5.124010,
            'longitude' => 105.337010,
            'photo_base64' => $fakeBase64Photo,
        ]);

        $clockInResponse->assertSessionHas('success');

        // Check attendance record
        $attendance = \App\Models\Attendance::where('student_id', $siswa->student->id)
            ->whereDate('date', Carbon::today()->format('Y-m-d'))
            ->first();

        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->check_in_time);
        $this->assertNotNull($attendance->check_in_distance);
        $this->assertEquals('hadir', $attendance->status);
        $this->assertTrue($attendance->is_check_in_within_radius);

        // Set time within clock-out window (14:30 WIB)
        Carbon::setTestNow(Carbon::today()->setTime(14, 30, 0));

        // 2. Test Clock-Out
        $clockOutResponse = $this->post('/siswa/attendances/clock-out', [
            'latitude' => -5.124015,
            'longitude' => 105.337015,
            'photo_base64' => $fakeBase64Photo,
        ]);

        $clockOutResponse->assertSessionHas('success');

        $attendance->refresh();
        $this->assertNotNull($attendance->check_out_time);
        $this->assertNotNull($attendance->check_out_distance);

        Carbon::setTestNow();
    }

    public function test_clock_in_time_rules_and_late_status(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);
        $fakeBase64Photo = 'data:image/jpeg;base64,' . base64_encode('fake-jpeg-binary-data');

        // Case 1: Clock in at 09:15 (between 08:00 - 09:30) -> status 'hadir'
        Carbon::setTestNow(Carbon::today()->setTime(9, 15, 0));
        $resOnTime = $this->post('/siswa/attendances/clock-in', [
            'latitude' => -5.124010,
            'longitude' => 105.337010,
            'photo_base64' => $fakeBase64Photo,
        ]);
        $resOnTime->assertSessionHas('success');
        $attOnTime = \App\Models\Attendance::where('student_id', $siswa->student->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->first();
        $this->assertEquals('hadir', $attOnTime->status);

        // Clean up attendance record for next check
        $attOnTime->delete();

        // Case 2: Clock in at 10:30 (between 09:31 - 13:50) -> status 'terlambat'
        Carbon::setTestNow(Carbon::today()->setTime(10, 30, 0));
        $resLate = $this->post('/siswa/attendances/clock-in', [
            'latitude' => -5.124010,
            'longitude' => 105.337010,
            'photo_base64' => $fakeBase64Photo,
        ]);
        $resLate->assertSessionHas('success');
        $attLate = \App\Models\Attendance::where('student_id', $siswa->student->id)
            ->whereDate('date', Carbon::today()->toDateString())
            ->first();
        $this->assertEquals('terlambat', $attLate->status);

        // Clean up
        $attLate->delete();

        // Case 3: Clock in at 13:55 (past 13:50) -> rejected with error
        Carbon::setTestNow(Carbon::today()->setTime(13, 55, 0));
        $resOver = $this->post('/siswa/attendances/clock-in', [
            'latitude' => -5.124010,
            'longitude' => 105.337010,
            'photo_base64' => $fakeBase64Photo,
        ]);
        $resOver->assertSessionHas('error');

        Carbon::setTestNow();
    }

    public function test_clock_out_time_window_rules(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);
        $fakeBase64Photo = 'data:image/jpeg;base64,' . base64_encode('fake-jpeg-binary-data');

        // Clock in at 08:30
        Carbon::setTestNow(Carbon::today()->setTime(8, 30, 0));
        $this->post('/siswa/attendances/clock-in', [
            'latitude' => -5.124010,
            'longitude' => 105.337010,
            'photo_base64' => $fakeBase64Photo,
        ]);

        // Attempt Clock-out at 12:30 (before 14:00) -> rejected
        Carbon::setTestNow(Carbon::today()->setTime(12, 30, 0));
        $resEarly = $this->post('/siswa/attendances/clock-out', [
            'latitude' => -5.124015,
            'longitude' => 105.337015,
            'photo_base64' => $fakeBase64Photo,
        ]);
        $resEarly->assertSessionHas('error');

        // Attempt Clock-out at 15:30 (between 14:00 - 17:00) -> accepted
        Carbon::setTestNow(Carbon::today()->setTime(15, 30, 0));
        $resValid = $this->post('/siswa/attendances/clock-out', [
            'latitude' => -5.124015,
            'longitude' => 105.337015,
            'photo_base64' => $fakeBase64Photo,
        ]);
        $resValid->assertSessionHas('success');

        Carbon::setTestNow();
    }

    public function test_haversine_distance_calculation(): void
    {
        // Distance between identical points should be 0
        $distSame = \App\Models\Attendance::calculateDistance(-7.7956, 110.3695, -7.7956, 110.3695);
        $this->assertEquals(0, $distSame);

        // Near point (approx ~100m)
        $distNear = \App\Models\Attendance::calculateDistance(-7.7956, 110.3695, -7.7960, 110.3700);
        $this->assertGreaterThan(0, $distNear);
        $this->assertLessThan(200, $distNear);
    }

    public function test_siswa_profile_page_does_not_have_change_password_form(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $response = $this->get('/siswa/profile');
        $response->assertStatus(200);
        $response->assertDontSee('Ganti Kata Sandi');
        $response->assertSee('Asal Instansi / Sekolah');
        $response->assertSee('Program Keahlian');
    }

    public function test_siswa_can_update_profile_biodata_with_program_and_school_origin(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $response = $this->put('/siswa/profile', [
            'program' => 'Rekayasa Perangkat Lunak (Coding)',
            'school_origin' => 'SMK Negeri 1 Batanghari',
            'phone' => '089876543210',
            'address' => 'Gg. Cendana, Banjar Rejo',
        ]);

        $response->assertSessionHas('success');

        $siswa->student->refresh();
        $this->assertEquals('Rekayasa Perangkat Lunak (Coding)', $siswa->student->program);
        $this->assertEquals('SMK Negeri 1 Batanghari', $siswa->student->school_origin);
        $this->assertEquals('089876543210', $siswa->student->phone);
    }

    public function test_siswa_school_origin_is_mandatory(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $response = $this->put('/siswa/profile', [
            'program' => 'Teknologi Informasi',
            'school_origin' => '', // Empty should fail
            'phone' => '081234567890',
        ]);

        $response->assertSessionHasErrors(['school_origin']);
    }

    public function test_siswa_cannot_access_password_update_route(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $response = $this->put('/siswa/profile/password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        // Route does not exist (404 or 405)
        $this->assertTrue(in_array($response->status(), [404, 405]));
    }

    public function test_sidebar_displays_nilai_dan_sertifikat_for_admin_and_siswa(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);
        $resAdmin = $this->get('/admin/dashboard');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Nilai & Sertifikat', false);

        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);
        $resSiswa = $this->get('/siswa/dashboard');
        $resSiswa->assertStatus(200);
        $resSiswa->assertSee('Nilai & Sertifikat', false);
    }

    public function test_published_certificate_and_6_criteria_grades_appear_on_siswa_page(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        // Create a published certificate with 6 criteria scores
        $cert = \App\Models\Certificate::create([
            'student_id' => $siswa->student->id,
            'name' => 'Sertifikat Keahlian Laravel & Tailwind',
            'certificate_number' => 'LS-CERT-TEST-PUB-01',
            'program' => 'Rekayasa Perangkat Lunak',
            'issued_date' => now()->toDateString(),
            'file' => 'certificates/sample.pdf',
            'is_published' => true,
            'score_discipline' => 95.0,
            'score_initiative' => 92.0,
            'score_teamwork' => 88.0,
            'score_responsibility' => 96.0,
            'score_attitude' => 98.0,
            'score_attendance' => 94.0,
            'final_score' => 93.8,
            'grade_predicate' => 'A (Sangat Baik)',
        ]);

        $response = $this->get('/siswa/certificates');
        $response->assertStatus(200);
        $response->assertSee('Sertifikat Keahlian Laravel & Tailwind');
        $response->assertSee('LS-CERT-TEST-PUB-01');
        // Check 6 criteria names appear on page
        $response->assertSee('Disiplin');
        $response->assertSee('Inisiatif');
        $response->assertSee('Kerja sama');
        $response->assertSee('T. Jawab');
        $response->assertSee('Sikap');
        $response->assertSee('Kehadiran');
        $response->assertSee('93.8');
    }

    public function test_unpublished_certificate_does_not_appear_on_siswa_page(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();

        // Remove any existing certificates for this student
        \App\Models\Certificate::where('student_id', $siswa->student->id)->delete();

        // Create an UNPUBLISHED certificate
        $draftCert = \App\Models\Certificate::create([
            'student_id' => $siswa->student->id,
            'name' => 'Draft Rahasia Sertifikat Belum Publish',
            'certificate_number' => 'LS-CERT-TEST-DRAFT-01',
            'program' => 'Teknologi Informasi',
            'issued_date' => now()->toDateString(),
            'file' => 'certificates/sample.pdf',
            'is_published' => false,
            'score_discipline' => 80.0,
            'score_initiative' => 80.0,
            'score_teamwork' => 80.0,
            'score_responsibility' => 80.0,
            'score_attitude' => 80.0,
            'score_attendance' => 80.0,
            'final_score' => 80.0,
        ]);

        $this->actingAs($siswa);
        $response = $this->get('/siswa/certificates');
        $response->assertStatus(200);
        // The draft certificate MUST NOT appear
        $response->assertDontSee('Draft Rahasia Sertifikat Belum Publish');
        $response->assertDontSee('LS-CERT-TEST-DRAFT-01');
        // Friendly notice when not published
        $response->assertSee('Belum Dipublikasikan');

        // Cannot download unpublished certificate
        $downloadRes = $this->get('/siswa/certificates/' . $draftCert->id . '/download');
        $downloadRes->assertStatus(403);
    }

    public function test_admin_can_toggle_publish_certificate(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $cert = \App\Models\Certificate::create([
            'student_id' => $siswa->student->id,
            'name' => 'Sertifikat Uji Publikasi',
            'certificate_number' => 'LS-CERT-TEST-TOGGLE-01',
            'program' => 'Teknologi Informasi',
            'issued_date' => now()->toDateString(),
            'file' => 'certificates/sample.pdf',
            'is_published' => false,
        ]);

        $this->assertFalse($cert->is_published);

        // Toggle to published
        $this->patch('/admin/certificates/' . $cert->id . '/toggle-publish');
        $cert->refresh();
        $this->assertTrue($cert->is_published);

        // Toggle back to draft
        $this->patch('/admin/certificates/' . $cert->id . '/toggle-publish');
        $cert->refresh();
        $this->assertFalse($cert->is_published);
    }

    public function test_admin_and_siswa_can_view_official_certificate_layout(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();

        $cert = \App\Models\Certificate::create([
            'student_id' => $siswa->student->id,
            'name' => 'Sertifikat Kelulusan Desain Grafis',
            'certificate_number' => 'LS-CERT-LAYOUT-01',
            'program' => 'Desain Grafis',
            'issued_date' => now()->toDateString(),
            'file' => 'certificates/sample.pdf',
            'is_published' => true,
            'score_discipline' => 90.0,
            'score_initiative' => 90.0,
            'score_teamwork' => 90.0,
            'score_responsibility' => 90.0,
            'score_attitude' => 90.0,
            'score_attendance' => 90.0,
            'final_score' => 90.0,
            'grade_predicate' => 'A (Sangat Baik)',
        ]);

        // Admin can view certificate
        $this->actingAs($admin);
        $resAdmin = $this->get('/admin/certificates/' . $cert->id . '/certificate');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('SERTIFIKAT KOMPETENSI');
        $resAdmin->assertSee('LS-CERT-LAYOUT-01');

        // Siswa can view published certificate
        $this->actingAs($siswa);
        $resSiswa = $this->get('/siswa/certificates/' . $cert->id . '/certificate');
        $resSiswa->assertStatus(200);
        $resSiswa->assertSee('SERTIFIKAT KOMPETENSI');
        $resSiswa->assertSee('LS-CERT-LAYOUT-01');
    }

    public function test_siswa_cannot_view_unpublished_certificate_layout(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();

        $draftCert = \App\Models\Certificate::create([
            'student_id' => $siswa->student->id,
            'name' => 'Sertifikat Rahasia Draft',
            'certificate_number' => 'LS-CERT-DRAFT-VIEW-01',
            'program' => 'Teknologi Informasi',
            'issued_date' => now()->toDateString(),
            'file' => 'certificates/sample.pdf',
            'is_published' => false,
        ]);

        $this->actingAs($siswa);
        $resSiswa = $this->get('/siswa/certificates/' . $draftCert->id . '/certificate');
        $resSiswa->assertStatus(403);
    }

    public function test_tidy_student_and_admin_attendance_views(): void
    {
        // 1. Verifikasi Halaman Siswa
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $resSiswa = $this->get('/siswa/attendances');
        $resSiswa->assertStatus(200);
        $resSiswa->assertSee('Presensi Siswa LKP Langgas Sinau');
        $resSiswa->assertSee('08.00 - 09.30 WIB');
        $resSiswa->assertSee('09.31 - 13.50 WIB');
        $resSiswa->assertSee('14.00 - 17.00 WIB');
        $resSiswa->assertSee('Status Presensi Hari Ini');
        $resSiswa->assertSee('Tabel Riwayat Presensi Saya');
        $resSiswa->assertDontSee('Materi / Sesi');

        // 2. Verifikasi Halaman Admin Absensi
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $resAdmin = $this->get('/admin/attendances');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('Daftar Absensi');
        $resAdmin->assertSee('Cari Siswa');
        $resAdmin->assertSee('Program Keahlian');
        $resAdmin->assertSee('Status Kehadiran');

        // 3. Verifikasi Halaman Admin Rekap
        $resRekap = $this->get('/admin/attendances/rekap');
        $resRekap->assertStatus(200);
        $resRekap->assertSee('Rekapitulasi Kehadiran Siswa');
        $resRekap->assertSee('Cetak Lembar');
    }

    public function test_admin_can_fill_custom_class_name_for_student(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        // 1. Cek view create student memiliki input text class_name
        $resCreate = $this->get('/admin/students/create');
        $resCreate->assertStatus(200);
        $resCreate->assertSee('name="class_name"', false);
        $resCreate->assertDontSee('name="class_id"', false);

        // 2. Buat siswa baru dengan mengisi class_name manual
        $studentData = [
            'name' => 'Siswa Kelas Custom',
            'email' => 'customclass@langgas-sinau.com',
            'password' => 'password123',
            'student_number' => 'LS-TEST-CLASS-999',
            'class_name' => 'Batch 2 - Desain Grafis Mandiri',
            'program' => 'Desain Grafis',
            'school_origin' => 'SMK Negeri Batanghari',
            'entry_date' => now()->toDateString(),
            'status' => 'aktif',
        ];

        $resStore = $this->post('/admin/students', $studentData);
        $resStore->assertRedirect('/admin/students');

        $student = Student::where('student_number', 'LS-TEST-CLASS-999')->first();
        $this->assertNotNull($student);
        $this->assertNotNull($student->class);
        $this->assertEquals('Batch 2 - Desain Grafis Mandiri', $student->class->name);

        // 3. Edit siswa dengan nama kelas baru manual
        $resUpdate = $this->put("/admin/students/{$student->id}", array_merge($studentData, [
            'class_name' => 'Batch 2 - Kelas Unggulan Siang',
        ]));
        $resUpdate->assertRedirect('/admin/students');

        $student->refresh();
        $this->assertEquals('Batch 2 - Kelas Unggulan Siang', $student->class->name);
    }

    public function test_certificate_has_mentor_and_mentor_name_customizable(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();

        // 1. Admin creates certificate with mentor name and leader name
        $this->actingAs($admin);
        $resCreate = $this->get('/admin/certificates/create');
        $resCreate->assertStatus(200);
        $resCreate->assertSee('name="mentor_name"', false);
        $resCreate->assertSee('name="leader_name"', false);
        $resCreate->assertSee('Catatan Evaluasi / Pesan Mentor');

        $certData = [
            'student_id' => $siswa->student->id,
            'name' => 'Sertifikat Pemrograman Web Tingkat Mahir',
            'certificate_number' => 'LS-CERT-MENTOR-001',
            'program' => 'Web Development',
            'issued_date' => now()->toDateString(),
            'mentor_name' => 'Budi Santoso, M.Kom.',
            'leader_name' => 'Drs. H. Riswan Setiawan, M.M.',
            'is_published' => 1,
            'score_discipline' => 95,
            'score_initiative' => 90,
            'score_teamwork' => 88,
            'score_responsibility' => 92,
            'score_attitude' => 96,
            'score_attendance' => 100,
            'assessment_notes' => 'Sangat kompeten dalam pengerjaan proyek akhir.',
        ];

        $resStore = $this->post('/admin/certificates', $certData);
        $resStore->assertRedirect('/admin/certificates');

        $cert = \App\Models\Certificate::where('certificate_number', 'LS-CERT-MENTOR-001')->first();
        $this->assertNotNull($cert);
        $this->assertEquals('Budi Santoso, M.Kom.', $cert->mentor_name);
        $this->assertEquals('Drs. H. Riswan Setiawan, M.M.', $cert->leader_name);

        // 2. View certificate - check mentor label, mentor name, and leader name
        $resCertView = $this->get('/admin/certificates/' . $cert->id . '/certificate');
        $resCertView->assertStatus(200);
        $resCertView->assertSee('Mentor,');
        $resCertView->assertSee('Budi Santoso, M.Kom.');
        $resCertView->assertSee('Drs. H. Riswan Setiawan, M.M.');
        $resCertView->assertDontSee('Instruktur / Penguji Kompetensi');

        // 3. Edit view has mentor_name and leader_name fields
        $resEdit = $this->get('/admin/certificates/' . $cert->id . '/edit');
        $resEdit->assertStatus(200);
        $resEdit->assertSee('name="mentor_name"', false);
        $resEdit->assertSee('name="leader_name"', false);
        $resEdit->assertSee('Budi Santoso, M.Kom.');
        $resEdit->assertSee('Drs. H. Riswan Setiawan, M.M.');
        $resEdit->assertSee('Catatan Evaluasi / Pesan Mentor');

        // 4. Update mentor name and leader name
        $updateData = array_merge($certData, [
            'mentor_name' => 'Rian Hidayat, S.Pd.',
            'leader_name' => 'Hj. Siti Rahmawati, S.E.',
        ]);
        $resUpdate = $this->put('/admin/certificates/' . $cert->id, $updateData);
        $resUpdate->assertRedirect('/admin/certificates');

        $cert->refresh();
        $this->assertEquals('Rian Hidayat, S.Pd.', $cert->mentor_name);
        $this->assertEquals('Hj. Siti Rahmawati, S.E.', $cert->leader_name);

        // 5. Siswa views certificate and sees updated mentor name & leader name
        $this->actingAs($siswa);
        $resSiswaView = $this->get('/siswa/certificates/' . $cert->id . '/certificate');
        $resSiswaView->assertStatus(200);
        $resSiswaView->assertSee('Mentor,');
        $resSiswaView->assertSee('Rian Hidayat, S.Pd.');
        $resSiswaView->assertSee('Hj. Siti Rahmawati, S.E.');

        // 6. Test fallback if mentor_name and leader_name are empty
        $cert->mentor_name = null;
        $cert->leader_name = null;
        $cert->save();

        $resFallback = $this->get('/siswa/certificates/' . $cert->id . '/certificate');
        $resFallback->assertStatus(200);
        $resFallback->assertSee('Mentor,');
        $resFallback->assertSee('Mentor Pelatihan');
        $resFallback->assertSee('Pimpinan LKP Langgas Sinau');
    }

    public function test_auto_alpa_command_and_admin_trigger(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        // 1. Admin trigger auto-alpa via POST
        $resPost = $this->post('/admin/attendances/auto-alpa', [
            'date' => now()->toDateString(),
        ]);
        $resPost->assertSessionHas('success');

        // 2. Test artisan command directly
        $exitCode = \Illuminate\Support\Facades\Artisan::call('attendance:auto-alpa', [
            '--force' => true,
        ]);
        $this->assertEquals(0, $exitCode);

        // 3. Admin index shows Jalankan Auto-Alpa button
        $resIndex = $this->get('/admin/attendances');
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Jalankan Auto-Alpa');
    }

    public function test_strict_radius_enforcement_blocks_distant_checkin(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $fakeBase64Photo = 'data:image/jpeg;base64,' . base64_encode('fake-jpeg-binary-data');

        // Set test time to on-time window
        Carbon::setTestNow(Carbon::today()->setTime(8, 45, 0));

        // Attempt clock-in far away (e.g. Jakarta, ~200km away)
        $resDistant = $this->post('/siswa/attendances/clock-in', [
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'photo_base64' => $fakeBase64Photo,
        ]);

        $resDistant->assertSessionHas('error');
        $this->assertTrue(str_contains(session('error'), 'Presensi ditolak!') || str_contains(session('error'), 'radius'));
    }
}


