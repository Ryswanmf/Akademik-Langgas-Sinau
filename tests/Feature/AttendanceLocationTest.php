<?php

namespace Tests\Feature;

use App\Models\AttendanceLocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceLocationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_access_attendance_locations_index(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $response = $this->get(route('admin.attendance-locations.index'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Titik GPS & Jam Absensi', false);
        $response->assertSee('V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung');
        $response->assertSee('-5.124188');
        $response->assertSee('105.332312');
        $response->assertSee('overview-map');
    }

    public function test_admin_can_access_create_attendance_location_page(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $response = $this->get(route('admin.attendance-locations.create'));
        $response->assertStatus(200);
        $response->assertSee('Pilih Titik Langsung dari Peta');
        $response->assertSee('V8GJ+8W Banjar Rejo');
        $response->assertSee('picker-map');
    }

    public function test_admin_can_store_new_attendance_location(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $response = $this->post(route('admin.attendance-locations.store'), [
            'name' => 'Kampus 2 Batanghari Baru',
            'address' => 'Jl. Merdeka No. 45, Banjar Rejo, Batanghari',
            'plus_code' => 'V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung',
            'latitude' => -5.124188,
            'longitude' => 105.332312,
            'radius_meters' => 200,
            'strict_radius' => 1,
            'in_start' => '07:30',
            'in_on_time_end' => '09:00',
            'in_late_end' => '13:30',
            'out_start' => '14:30',
            'out_end' => '17:30',
            'auto_alpa_time' => '17:30',
            'working_days' => [1, 2, 3, 4, 5],
            'is_active' => 1,
            'description' => 'Gedung ruang kelas baru',
        ]);

        $response->assertRedirect(route('admin.attendance-locations.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('attendance_locations', [
            'name' => 'Kampus 2 Batanghari Baru',
            'radius_meters' => 200,
            'in_start' => '07:30',
        ]);
    }

    public function test_admin_can_access_edit_attendance_location_page(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $location = AttendanceLocation::first();

        $response = $this->get(route('admin.attendance-locations.edit', $location));
        $response->assertStatus(200);
        $response->assertSee('Edit Titik GPS');
        $response->assertSee($location->name);
        $response->assertSee('picker-map');
    }

    public function test_admin_can_update_attendance_location(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $location = AttendanceLocation::first();

        $response = $this->put(route('admin.attendance-locations.update', $location), [
            'name' => 'Nama Lokasi Terupdate',
            'address' => 'Banjar Rejo, Lampung Timur',
            'plus_code' => 'V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung',
            'latitude' => -5.124188,
            'longitude' => 105.332312,
            'radius_meters' => 180,
            'strict_radius' => 1,
            'in_start' => '08:00',
            'in_on_time_end' => '09:30',
            'in_late_end' => '13:50',
            'out_start' => '14:00',
            'out_end' => '17:00',
            'auto_alpa_time' => '17:00',
            'working_days' => [1, 2, 3, 4, 5, 6],
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.attendance-locations.index'));
        $response->assertSessionHas('success');

        $location->refresh();
        $this->assertEquals('Nama Lokasi Terupdate', $location->name);
        $this->assertEquals(180, $location->radius_meters);
    }

    public function test_admin_can_toggle_active_status(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        // Ada 2 lokasi yang diseed
        $location = AttendanceLocation::latest('id')->first();
        $currentStatus = $location->is_active;

        $response = $this->patch(route('admin.attendance-locations.toggle-active', $location));
        $response->assertSessionHas('success');

        $location->refresh();
        $this->assertEquals(!$currentStatus, $location->is_active);
    }

    public function test_admin_cannot_deactivate_the_only_active_location(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        // Sisakan 1 aktif
        AttendanceLocation::where('id', '>', 1)->delete();
        $location = AttendanceLocation::first();
        $location->update(['is_active' => true]);

        $response = $this->patch(route('admin.attendance-locations.toggle-active', $location));
        $response->assertSessionHas('error');

        $location->refresh();
        $this->assertTrue($location->is_active);
    }

    public function test_siswa_cannot_access_attendance_locations_admin_routes(): void
    {
        $siswa = User::where('email', 'siswa@langgas-sinau.com')->first();
        $this->actingAs($siswa);

        $response = $this->get(route('admin.attendance-locations.index'));
        $response->assertRedirect(route('siswa.dashboard'));
    }

    public function test_admin_can_record_attendance_with_map_coordinates(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $student = \App\Models\Student::first();
        $primary = AttendanceLocation::getPrimary();

        $response = $this->post(route('admin.attendances.store'), [
            'student_id' => $student->id,
            'date' => now()->toDateString(),
            'status' => 'hadir',
            'check_in_lat' => -5.124188,
            'check_in_lng' => 105.332312,
            'attendance_location_id' => $primary->id,
            'note' => 'Presensi via peta admin',
        ]);

        $response->assertRedirect(route('admin.attendances.index'));

        $recorded = \App\Models\Attendance::where('student_id', $student->id)
            ->where('note', 'Presensi via peta admin')
            ->first();

        $this->assertNotNull($recorded);
        $this->assertEquals('hadir', $recorded->status);
        $this->assertEquals($primary->id, $recorded->attendance_location_id);
        $this->assertEquals(-5.124188, $recorded->check_in_lat);
        $this->assertEquals(105.332312, $recorded->check_in_lng);
        $this->assertNotNull($recorded->check_in_distance);
    }

    public function test_admin_can_edit_attendance_location_and_coordinates(): void
    {
        $admin = User::where('email', 'admin@langgas-sinau.com')->first();
        $this->actingAs($admin);

        $student = \App\Models\Student::first();
        $attendance = \App\Models\Attendance::create([
            'student_id' => $student->id,
            'date' => now()->subDay()->toDateString(),
            'status' => 'hadir',
            'check_in_lat' => -5.1240,
            'check_in_lng' => 105.3370,
        ]);

        $primary = AttendanceLocation::getPrimary();

        $response = $this->put(route('admin.attendances.update', $attendance), [
            'status' => 'terlambat',
            'check_in_time' => '09:45',
            'check_in_lat' => -5.124188,
            'check_in_lng' => 105.332312,
            'attendance_location_id' => $primary->id,
            'note' => 'Diperbarui dengan peta',
        ]);

        $response->assertRedirect(route('admin.attendances.index'));
        $attendance->refresh();
        $this->assertEquals('terlambat', $attendance->status);
        $this->assertEquals(-5.124188, $attendance->check_in_lat);
        $this->assertEquals(105.332312, $attendance->check_in_lng);
        $this->assertEquals($primary->id, $attendance->attendance_location_id);
    }
}
