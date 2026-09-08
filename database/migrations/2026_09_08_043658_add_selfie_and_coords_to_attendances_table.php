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
        Schema::table('attendances', function (Blueprint $table) {
            $table->time('check_in_time')->nullable()->after('date');
            $table->time('check_out_time')->nullable()->after('check_in_time');
            $table->string('check_in_photo')->nullable()->after('check_out_time');
            $table->string('check_out_photo')->nullable()->after('check_in_photo');
            $table->decimal('check_in_lat', 10, 7)->nullable()->after('check_out_photo');
            $table->decimal('check_in_lng', 10, 7)->nullable()->after('check_in_lat');
            $table->integer('check_in_distance')->nullable()->after('check_in_lng'); // dalam meter
            $table->decimal('check_out_lat', 10, 7)->nullable()->after('check_in_distance');
            $table->decimal('check_out_lng', 10, 7)->nullable()->after('check_out_lat');
            $table->integer('check_out_distance')->nullable()->after('check_out_lng'); // dalam meter
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'check_in_time',
                'check_out_time',
                'check_in_photo',
                'check_out_photo',
                'check_in_lat',
                'check_in_lng',
                'check_in_distance',
                'check_out_lat',
                'check_out_lng',
                'check_out_distance',
            ]);
        });
    }
};
