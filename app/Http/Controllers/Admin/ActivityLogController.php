<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user');

        // Filters
        if ($request->filled('user_id')) {
            $query->forUser($request->user_id);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->betweenDates($request->start_date, $request->end_date);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $logs = $query->latest()->paginate(20);

        // Statistics
        $totalLogs = ActivityLog::count();
        $todayLogs = ActivityLog::whereDate('created_at', today())->count();
        $uniqueUsers = ActivityLog::distinct('user_id')->count('user_id');

        // Recent actions for filter dropdown
        $recentActions = ActivityLog::distinct('action')
            ->select('action')
            ->orderBy('action')
            ->limit(20)
            ->pluck('action');

        // Users for filter dropdown
        $users = User::select('id', 'name', 'email')->get();

        return view('admin.activity-logs.index', compact(
            'logs',
            'totalLogs',
            'todayLogs',
            'uniqueUsers',
            'recentActions',
            'users'
        ));
    }

    /**
     * Export activity logs to CSV
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->forUser($request->user_id);
        }

        if ($request->filled('action')) {
            $query->byAction($request->action);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->betweenDates($request->start_date, $request->end_date);
        }

        $logs = $query->latest()->get();

        $csvData = "Date,User,Action,Description,IP Address\n";
        
        foreach ($logs as $log) {
            $csvData .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user ? $log->user->name : 'Système',
                $log->action,
                str_replace('"', '""', $log->description ?? ''),
                $log->ip_address ?? ''
            );
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="activity_logs_' . date('Y-m-d') . '.csv"');
    }
}