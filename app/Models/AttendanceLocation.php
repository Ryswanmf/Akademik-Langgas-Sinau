<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'plus_code',
        'latitude',
        'longitude',
        'radius_meters',
        'strict_radius',
        'in_start',
        'in_on_time_end',
        'in_late_end',
        'out_start',
        'out_end',
        'auto_alpa_time',
        'working_days',
        'is_active',
        'description',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meters' => 'integer',
        'strict_radius' => 'boolean',
        'is_active' => 'boolean',
        'working_days' => 'array',
    ];

    /**
     * Relasi ke data presensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope lokasi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ambil lokasi utama (default lokasi aktif)
     */
    public static function getPrimary(): ?self
    {
        $location = self::where('is_active', true)->first();

        if (!$location) {
            // Fallback: jika belum ada data di tabel, buat atau ambil dari config attendance
            $location = self::create([
                'name' => 'LKP Langgas Sinau Akademi (Kampus Utama)',
                'address' => config('attendance.address', 'Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung'),
                'plus_code' => 'V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung',
                'latitude' => -5.124188,
                'longitude' => 105.332312,
                'radius_meters' => (int) config('attendance.radius_meters', 150),
                'strict_radius' => (bool) config('attendance.strict_radius', true),
                'in_start' => config('attendance.in_start', '08:00'),
                'in_on_time_end' => config('attendance.in_on_time_end', '09:30'),
                'in_late_end' => config('attendance.in_late_end', '13:50'),
                'out_start' => config('attendance.out_start', '14:00'),
                'out_end' => config('attendance.out_end', '17:00'),
                'auto_alpa_time' => config('attendance.auto_alpa_time', '17:00'),
                'working_days' => config('attendance.working_days', [1, 2, 3, 4, 5, 6]),
                'is_active' => true,
                'description' => 'Titik lokasi resmi presensi LKP Langgas Sinau berdasarkan Google Plus Code V8GJ+8W Banjar Rejo.',
            ]);
        }

        return $location;
    }

    /**
     * Cari lokasi aktif terdekat dari koordinat user
     */
    public static function findNearestActive(float $userLat, float $userLng): ?array
    {
        $locations = self::where('is_active', true)->get();

        if ($locations->isEmpty()) {
            $primary = self::getPrimary();
            $locations = collect([$primary]);
        }

        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($locations as $loc) {
            $distance = self::calculateDistance($userLat, $userLng, (float) $loc->latitude, (float) $loc->longitude);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $loc;
            }
        }

        if (!$nearest) {
            return null;
        }

        return [
            'location' => $nearest,
            'distance' => $minDistance,
        ];
    }

    /**
     * Formula Haversine menghitung jarak antara 2 titik koordinat (dalam meter)
     */
    public static function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): int
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return (int) round($earthRadius * $c);
    }

    /**
     * Hitung jarak dari koordinat user ke titik lokasi ini (dalam meter)
     */
    public function distanceFrom(float $userLat, float $userLng): int
    {
        return self::calculateDistance($userLat, $userLng, (float) $this->latitude, (float) $this->longitude);
    }

    /**
     * Link Google Maps untuk verifikasi visual langsung
     */
    public function getGoogleMapsUrlAttribute(): string
    {
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    /**
     * Teks hari operasional
     */
    public function getWorkingDaysTextAttribute(): string
    {
        $days = $this->working_days ?? [1, 2, 3, 4, 5, 6];
        $map = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            0 => 'Minggu',
        ];

        $names = array_map(fn($d) => $map[$d] ?? $d, $days);
        return implode(', ', $names);
    }

    /**
     * Cek apakah hari ini adalah hari kerja/pelatihan
     */
    public function isWorkingDay(Carbon $date = null): bool
    {
        $date = $date ?? Carbon::today();
        $days = $this->working_days ?? [1, 2, 3, 4, 5, 6];
        return in_array($date->dayOfWeek, $days);
    }
}
