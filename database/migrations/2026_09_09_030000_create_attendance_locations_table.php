<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('plus_code')->nullable(); // Google Plus Code e.g. V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('radius_meters')->default(150); // radius toleransi (meter)
            $table->boolean('strict_radius')->default(true); // wajib dalam radius
            
            // Waktu Absensi Masuk & Pulang
            $table->string('in_start', 5)->default('08:00'); // Jam mulai presensi masuk
            $table->string('in_on_time_end', 5)->default('09:30'); // Batas hadir tepat waktu
            $table->string('in_late_end', 5)->default('13:50'); // Batas toleransi terlambat / jam masuk ditutup
            
            $table->string('out_start', 5)->default('14:00'); // Jam mulai presensi pulang
            $table->string('out_end', 5)->default('17:00'); // Batas akhir presensi pulang
            $table->string('auto_alpa_time', 5)->default('17:00'); // Jam batas auto-alpa
            
            $table->json('working_days')->nullable(); // [1, 2, 3, 4, 5, 6] (1=Senin s/d 6=Sabtu)
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Relasikan attendance_location_id pada tabel attendances jika ada
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('attendance_location_id')->nullable()->after('schedule_id')->constrained('attendance_locations')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropForeign(['attendance_location_id']);
                $table->dropColumn('attendance_location_id');
            });
        }

        Schema::dropIfExists('attendance_locations');
    }
};
