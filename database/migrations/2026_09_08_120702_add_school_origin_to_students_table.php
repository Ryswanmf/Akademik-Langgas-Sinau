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
        if (!Schema::hasColumn('students', 'school_origin')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('school_origin')->nullable()->after('program');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('students', 'school_origin')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('school_origin');
            });
        }
    }
};
