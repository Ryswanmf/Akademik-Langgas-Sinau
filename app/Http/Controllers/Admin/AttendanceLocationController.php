<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLocation;
use Illuminate\Http\Request;

class AttendanceLocationController extends Controller
{
    /**
     * Tampilkan daftar titik GPS dan aturan waktu absensi
     */
    public function index(Request $request)
    {
        $query = AttendanceLocation::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('plus_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        $locations = $query->paginate(10)->withQueryString();

        // Data statistik
        $totalLocations = AttendanceLocation::count();
        $activeLocations = AttendanceLocation::where('is_active', true)->count();
        $primaryLocation = AttendanceLocation::getPrimary();

        // Data koordinat titik aktif untuk render visual peta leaflet di index
        $mapLocations = AttendanceLocation::where('is_active', true)->get()->map(function ($loc) {
            return [
                'id' => $loc->id,
                'name' => $loc->name,
                'address' => $loc->address,
                'plus_code' => $loc->plus_code,
                'lat' => (float) $loc->latitude,
                'lng' => (float) $loc->longitude,
                'radius' => (int) $loc->radius_meters,
                'in_start' => $loc->in_start,
                'in_on_time_end' => $loc->in_on_time_end,
                'in_late_end' => $loc->in_late_end,
                'out_start' => $loc->out_start,
                'out_end' => $loc->out_end,
                'is_active' => $loc->is_active,
            ];
        });

        return view('admin.attendance-locations.index', compact(
            'locations',
            'totalLocations',
            'activeLocations',
            'primaryLocation',
            'mapLocations'
        ));
    }

    /**
     * Form tambah titik GPS baru
     */
    public function create(Request $request)
    {
        $defaultLocation = [
            'name' => '',
            'address' => 'Banjar Rejo, Kec. Batanghari, Kabupaten Lampung Timur, Lampung',
            'plus_code' => 'V8GJ+8W Banjar Rejo, Kabupaten Lampung Timur, Lampung',
            'latitude' => $request->filled('lat') ? (float) $request->lat : -5.124188,
            'longitude' => $request->filled('lng') ? (float) $request->lng : 105.332312,
            'radius_meters' => 150,
            'strict_radius' => true,
            'in_start' => '08:00',
            'in_on_time_end' => '09:30',
            'in_late_end' => '13:50',
            'out_start' => '14:00',
            'out_end' => '17:00',
            'auto_alpa_time' => '17:00',
            'working_days' => [1, 2, 3, 4, 5, 6],
            'is_active' => true,
            'description' => '',
        ];

        return view('admin.attendance-locations.create', compact('defaultLocation'));
    }

    /**
     * Simpan titik GPS dan waktu absensi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'plus_code' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:50000'],
            'strict_radius' => ['nullable', 'boolean'],
            'in_start' => ['required', 'date_format:H:i'],
            'in_on_time_end' => ['required', 'date_format:H:i'],
            'in_late_end' => ['required', 'date_format:H:i'],
            'out_start' => ['required', 'date_format:H:i'],
            'out_end' => ['required', 'date_format:H:i'],
            'auto_alpa_time' => ['required', 'date_format:H:i'],
            'working_days' => ['nullable', 'array'],
            'working_days.*' => ['integer', 'in:0,1,2,3,4,5,6'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['strict_radius'] = $request->has('strict_radius');
        $validated['is_active'] = $request->has('is_active');
        $validated['working_days'] = array_map('intval', $request->input('working_days', [1, 2, 3, 4, 5, 6]));

        AttendanceLocation::create($validated);

        return redirect()
            ->route('admin.attendance-locations.index')
            ->with('success', 'Titik lokasi GPS dan aturan waktu absensi berhasil ditambahkan.');
    }

    /**
     * Form edit titik GPS
     */
    public function edit(AttendanceLocation $attendanceLocation)
    {
        return view('admin.attendance-locations.edit', compact('attendanceLocation'));
    }

    /**
     * Update titik GPS dan waktu absensi
     */
    public function update(Request $request, AttendanceLocation $attendanceLocation)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'plus_code' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius_meters' => ['required', 'integer', 'min:10', 'max:50000'],
            'strict_radius' => ['nullable', 'boolean'],
            'in_start' => ['required', 'date_format:H:i'],
            'in_on_time_end' => ['required', 'date_format:H:i'],
            'in_late_end' => ['required', 'date_format:H:i'],
            'out_start' => ['required', 'date_format:H:i'],
            'out_end' => ['required', 'date_format:H:i'],
            'auto_alpa_time' => ['required', 'date_format:H:i'],
            'working_days' => ['nullable', 'array'],
            'working_days.*' => ['integer', 'in:0,1,2,3,4,5,6'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['strict_radius'] = $request->has('strict_radius');
        $validated['is_active'] = $request->has('is_active');
        $validated['working_days'] = array_map('intval', $request->input('working_days', [1, 2, 3, 4, 5, 6]));

        $attendanceLocation->update($validated);

        return redirect()
            ->route('admin.attendance-locations.index')
            ->with('success', 'Data titik GPS dan pengaturan waktu absensi berhasil diperbarui.');
    }

    /**
     * Toggle status aktif / nonaktif
     */
    public function toggleActive(AttendanceLocation $attendanceLocation)
    {
        $newStatus = !$attendanceLocation->is_active;

        // Jangan izinkan menonaktifkan jika ini adalah satu-satunya lokasi aktif
        if (!$newStatus) {
            $otherActive = AttendanceLocation::where('is_active', true)
                ->where('id', '!=', $attendanceLocation->id)
                ->count();

            if ($otherActive === 0) {
                return back()->with('error', 'Gagal menonaktifkan! Minimal harus ada 1 titik GPS presensi yang aktif.');
            }
        }

        $attendanceLocation->update(['is_active' => $newStatus]);

        $msg = $newStatus ? 'Titik lokasi GPS berhasil diaktifkan.' : 'Titik lokasi GPS dinonaktifkan.';
        return back()->with('success', $msg);
    }

    /**
     * Hapus titik GPS
     */
    public function destroy(AttendanceLocation $attendanceLocation)
    {
        // Cek jika ini satu-satunya lokasi
        $total = AttendanceLocation::count();
        if ($total <= 1) {
            return back()->with('error', 'Gagal menghapus! Sistem membutuhkan minimal 1 titik GPS sebagai acuan presensi.');
        }

        // Cek jika ini lokasi aktif terakhir
        if ($attendanceLocation->is_active) {
            $otherActive = AttendanceLocation::where('is_active', true)
                ->where('id', '!=', $attendanceLocation->id)
                ->count();

            if ($otherActive === 0) {
                return back()->with('error', 'Gagal menghapus! Titik GPS ini merupakan satu-satunya lokasi aktif. Silakan aktifkan titik lokasi lain terlebih dahulu.');
            }
        }

        $attendanceLocation->delete();

        return redirect()
            ->route('admin.attendance-locations.index')
            ->with('success', 'Titik lokasi GPS berhasil dihapus.');
    }
}
