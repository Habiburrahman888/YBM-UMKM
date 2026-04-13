<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Users;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('causer')->latest();

        // ── Filter: event ──────────────────────────────────────────────────
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // ── Filter: log_name / channel ─────────────────────────────────────
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }

        // ── Filter: causer (user) ──────────────────────────────────────────
        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }

        // ── Filter: role ───────────────────────────────────────────────────
        if ($request->filled('causer_role')) {
            $query->where('causer_role', $request->causer_role);
        }

        // ── Filter: subject type ───────────────────────────────────────────
        if ($request->filled('subject_type')) {
            $query->where('subject_type', 'like', '%' . $request->subject_type . '%');
        }

        // ── Filter: tanggal ────────────────────────────────────────────────
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        // ── Filter: search keyword ─────────────────────────────────────────
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('causer_name', 'like', "%{$q}%")
                    ->orWhere('subject_label', 'like', "%{$q}%")
                    ->orWhere('ip_address', 'like', "%{$q}%");
            });
        }

        $logs = $query->paginate(30)->withQueryString();

        // ── Stats ──────────────────────────────────────────────────────────
        $stats = [
            'total'   => ActivityLog::count(),
            'today'   => ActivityLog::whereDate('created_at', today())->count(),
            'logins'  => ActivityLog::where('event', 'login')->whereDate('created_at', today())->count(),
            'deletes' => ActivityLog::where('event', 'delete')->whereDate('created_at', today())->count(),
        ];

        // ── Event counts (untuk chart) ─────────────────────────────────────
        $eventCounts = ActivityLog::selectRaw('event, count(*) as total')
            ->groupBy('event')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'event');

        // ── Activity per hari (7 hari terakhir) ────────────────────────────
        $dailyActivity = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            return [
                'label' => $date->translatedFormat('D, d M'),
                'count' => ActivityLog::whereDate('created_at', $date)->count(),
            ];
        });

        // ── Users list (untuk filter dropdown) ────────────────────────────
        $userList = Users::orderBy('username')->get(['id', 'username', 'email', 'role']);

        // ── Event list (untuk filter dropdown) ────────────────────────────
        $eventList = ActivityLog::distinct()->pluck('event')->sort()->values();

        return view('admin.activity-log.index', compact(
            'logs',
            'stats',
            'eventCounts',
            'dailyActivity',
            'userList',
            'eventList'
        ));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('admin.activity-log.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->back()->with('success', 'Log berhasil dihapus.');
    }

    public function destroyBulk(Request $request)
    {
        $request->validate([
            'before_date' => 'nullable|date',
            'confirm'     => 'required|in:yes',
        ]);

        $query = ActivityLog::query();

        if ($request->filled('before_date')) {
            $query->whereDate('created_at', '<', $request->before_date);
        }

        $count = $query->count();
        $query->delete();

        return redirect()->route('admin.activity-log.index')
            ->with('success', "{$count} log berhasil dihapus.");
    }
}
