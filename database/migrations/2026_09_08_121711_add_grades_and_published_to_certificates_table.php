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
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('file')->index();
            }
            if (!Schema::hasColumn('certificates', 'score_discipline')) {
                $table->decimal('score_discipline', 5, 2)->nullable()->after('is_published');
            }
            if (!Schema::hasColumn('certificates', 'score_initiative')) {
                $table->decimal('score_initiative', 5, 2)->nullable()->after('score_discipline');
            }
            if (!Schema::hasColumn('certificates', 'score_teamwork')) {
                $table->decimal('score_teamwork', 5, 2)->nullable()->after('score_initiative');
            }
            if (!Schema::hasColumn('certificates', 'score_responsibility')) {
                $table->decimal('score_responsibility', 5, 2)->nullable()->after('score_teamwork');
            }
            if (!Schema::hasColumn('certificates', 'score_attitude')) {
                $table->decimal('score_attitude', 5, 2)->nullable()->after('score_responsibility');
            }
            if (!Schema::hasColumn('certificates', 'score_attendance')) {
                $table->decimal('score_attendance', 5, 2)->nullable()->after('score_attitude');
            }
            if (!Schema::hasColumn('certificates', 'final_score')) {
                $table->decimal('final_score', 5, 2)->nullable()->after('score_attendance');
            }
            if (!Schema::hasColumn('certificates', 'grade_predicate')) {
                $table->string('grade_predicate', 20)->nullable()->after('final_score');
            }
            if (!Schema::hasColumn('certificates', 'assessment_notes')) {
                $table->text('assessment_notes')->nullable()->after('grade_predicate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach ([
                'is_published',
                'score_discipline',
                'score_initiative',
                'score_teamwork',
                'score_responsibility',
                'score_attitude',
                'score_attendance',
                'final_score',
                'grade_predicate',
                'assessment_notes',
            ] as $col) {
                if (Schema::hasColumn('certificates', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
