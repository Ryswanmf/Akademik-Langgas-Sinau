<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'student_id',
        'schedule_id',
        'date',
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
        'status',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in_lat' => 'float',
            'check_in_lng' => 'float',
            'check_in_distance' => 'integer',
            'check_out_lat' => 'float',
            'check_out_lng' => 'float',
            'check_out_distance' => 'integer',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Hitung jarak dua koordinat GPS menggunakan formula Haversine (hasil dalam meter).
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return (int) round($angle * $earthRadius);
    }

    /**
     * URL Foto Swafoto Masuk
     */
    public function getCheckInPhotoUrlAttribute(): ?string
    {
        if (!$this->check_in_photo) {
            return null;
        }

        if (str_starts_with($this->check_in_photo, 'http')) {
            return $this->check_in_photo;
        }

        return asset($this->check_in_photo);
    }

    /**
     * URL Foto Swafoto Pulang
     */
    public function getCheckOutPhotoUrlAttribute(): ?string
    {
        if (!$this->check_out_photo) {
            return null;
        }

        if (str_starts_with($this->check_out_photo, 'http')) {
            return $this->check_out_photo;
        }

        return asset($this->check_out_photo);
    }

    /**
     * Format jam masuk
     */
    public function getFormattedCheckInTimeAttribute(): string
    {
        if (!$this->check_in_time) {
            return '-';
        }

        return substr($this->check_in_time, 0, 5) . ' WIB';
    }

    /**
     * Format jam pulang
     */
    public function getFormattedCheckOutTimeAttribute(): string
    {
        if (!$this->check_out_time) {
            return '-';
        }

        return substr($this->check_out_time, 0, 5) . ' WIB';
    }

    /**
     * Format jarak masuk
     */
    public function getCheckInDistanceFormattedAttribute(): string
    {
        if ($this->check_in_distance === null) {
            return '-';
        }

        if ($this->check_in_distance < 1000) {
            return $this->check_in_distance . ' m';
        }

        return round($this->check_in_distance / 1000, 1) . ' km';
    }

    /**
     * Cek apakah koordinat saat masuk berada dalam radius LKP
     */
    public function getIsCheckInWithinRadiusAttribute(): bool
    {
        if ($this->check_in_distance === null) {
            return true;
        }

        $maxRadius = config('attendance.radius_meters', 150);
        return $this->check_in_distance <= $maxRadius;
    }
}

