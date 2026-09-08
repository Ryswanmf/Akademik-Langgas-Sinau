<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'class_id',
        'student_number',
        'phone',
        'address',
        'photo',
        'program',
        'school_origin',
        'entry_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user ? $this->user->name : 'Siswa') . '&background=0D8ABC&color=fff&size=200';
    }

    public function attendanceStats(): array
    {
        $total = $this->attendances()->count();
        $hadir = $this->attendances()->where('status', 'hadir')->count();
        $terlambat = $this->attendances()->where('status', 'terlambat')->count();
        $izin = $this->attendances()->where('status', 'izin')->count();
        $sakit = $this->attendances()->where('status', 'sakit')->count();
        $alpa = $this->attendances()->where('status', 'alpa')->count();
        $rate = $total > 0 ? round((($hadir + $terlambat) / $total) * 100, 1) : 0;

        return [
            'total' => $total,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'rate' => $rate,
        ];
    }
}
