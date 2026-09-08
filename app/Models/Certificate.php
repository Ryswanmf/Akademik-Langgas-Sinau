<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'certificate_number',
        'program',
        'mentor_name',
        'leader_name',
        'issued_date',
        'file',
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
    ];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
            'is_published' => 'boolean',
            'score_discipline' => 'float',
            'score_initiative' => 'float',
            'score_teamwork' => 'float',
            'score_responsibility' => 'float',
            'score_attitude' => 'float',
            'score_attendance' => 'float',
            'final_score' => 'float',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public static function criteriaDefinitions(): array
    {
        return [
            'score_discipline' => [
                'label' => 'Disiplin',
                'description' => 'Ketaatan terhadap waktu, aturan, dan tata tertib LKP.',
                'icon' => 'clock',
            ],
            'score_initiative' => [
                'label' => 'Inisiatif & Kreatifitas',
                'description' => 'Kemampuan berpikir kreatif, inovatif, dan proaktif dalam tugas.',
                'icon' => 'lightbulb',
            ],
            'score_teamwork' => [
                'label' => 'Kerja sama',
                'description' => 'Kemampuan berkolaborasi dan komunikasi aktif dalam tim.',
                'icon' => 'users',
            ],
            'score_responsibility' => [
                'label' => 'Tanggung Jawab',
                'description' => 'Komitmen dan penyelesaian tugas tuntas sesuai tenggat waktu.',
                'icon' => 'check-badge',
            ],
            'score_attitude' => [
                'label' => 'Sikap',
                'description' => 'Etika, sopan santun, integritas, dan kepribadian terpuji.',
                'icon' => 'heart',
            ],
            'score_attendance' => [
                'label' => 'Kehadiran',
                'description' => 'Konsistensi kehadiran belajar dan ketepatan waktu presensi.',
                'icon' => 'calendar-check',
            ],
        ];
    }

    public static function determinePredicate(?float $score): ?string
    {
        if ($score === null) {
            return null;
        }

        if ($score >= 85) {
            return 'A (Sangat Baik)';
        } elseif ($score >= 75) {
            return 'B (Baik)';
        } elseif ($score >= 60) {
            return 'C (Cukup)';
        } else {
            return 'D (Kurang)';
        }
    }

    public function computeFinalScore(): ?float
    {
        $scores = array_filter([
            $this->score_discipline,
            $this->score_initiative,
            $this->score_teamwork,
            $this->score_responsibility,
            $this->score_attitude,
            $this->score_attendance,
        ], fn ($val) => !is_null($val));

        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }
}
