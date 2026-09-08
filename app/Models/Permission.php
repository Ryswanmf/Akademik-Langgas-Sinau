<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'type',
        'reason',
        'evidence',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
