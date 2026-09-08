<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::with(['student.user', 'student.class'])->latest('date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $permissions = $query->paginate(10)->withQueryString();

        $pendingCount = Permission::where('status', 'menunggu')->count();
        $approvedCount = Permission::where('status', 'disetujui')->count();
        $rejectedCount = Permission::where('status', 'ditolak')->count();

        return view('admin.permissions.index', compact('permissions', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function show(Permission $permission)
    {
        $permission->load(['student.user', 'student.class']);
        return view('admin.permissions.show', compact('permission'));
    }

    public function updateStatus(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:disetujui,ditolak'],
            'admin_note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($permission, $validated) {
            $permission->update([
                'status' => $validated['status'],
                'admin_note' => $validated['admin_note'] ?? null,
            ]);

            // If approved, automatically sync attendance status
            if ($validated['status'] === 'disetujui') {
                $statusType = in_array(strtolower($permission->type), ['sakit', 'izin']) ? strtolower($permission->type) : 'izin';
                Attendance::updateOrCreate(
                    [
                        'student_id' => $permission->student_id,
                        'date' => $permission->date,
                    ],
                    [
                        'status' => $statusType,
                        'note' => 'Izin disetujui: ' . $permission->reason,
                    ]
                );
            }
        });

        return redirect()->route('admin.permissions.index')->with('success', 'Status pengajuan izin berhasil diperbarui menjadi ' . ucfirst($validated['status']) . '.');
    }
}
