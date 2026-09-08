<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $announcements = Announcement::published()
            ->latest('published_at')
            ->paginate(10);

        return view('siswa.announcements', compact('student', 'announcements'));
    }

    public function show(Announcement $announcement)
    {
        $student = auth()->user()->student;

        if ($announcement->status !== 'published') {
            abort(404);
        }

        return view('siswa.announcement-detail', compact('student', 'announcement'));
    }
}
