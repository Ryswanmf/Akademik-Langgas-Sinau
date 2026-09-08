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
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'terlambat', 'izin', 'sakit', 'alpa') NOT NULL DEFAULT 'hadir'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (\Illuminate\Support\Facades\DB::getDriverName() === 'mysql') {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('hadir', 'izin', 'sakit', 'alpa') NOT NULL DEFAULT 'hadir'");
        }
    }
};
