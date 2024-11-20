<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $recentApplications = $user->jobApplications()
            ->with('company', 'status')
            ->latest()
            ->take(5)
            ->get();

        $statusCounts = $user->jobApplications()
            ->with('status')
            ->get()
            ->groupBy('status.name')
            ->map(fn($items) => $items->count());

        $upcomingInterviews = $user->jobApplications()
            ->whereHas('notes', function($query) {
                $query->where('type','interview')
                ->where('reminder_date','>=', now())
                ->where('reminder_date','<=', now()->addDays(7));
            })
            ->with(['company','notes' => function($query) {
                $query->where('type','interview')
                ->where('reminder_date','>=', now())
                ->where('reminder_date','<=', now()->addDays(7));
            }])
            ->get();

        $activitySummary = [
            'applications_this_week' => $user->jobApplications()
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'active_applications'=> $user->jobApplications()
                ->whereHas('status', fn($query) => 
                    $query->whereNotIn('status',['Rejected','Withdrawn','Accepted'])
                )->count(),
            'interviews_rate' => $upcomingInterviews->count(),
            'response_rate' => $user->jobApplications()
                ->whereHas('status', fn($query) => 
                    $query->whereIn('name',['Phone Screen','Interview','Offer','Accepted'])
                )->count() / max($user->jobApplications()->count(), 1) * 100,
        ];

        return Inertia::render('Dashboard', [
            'recentApplications' => $recentApplications,
            'statusCounts' => $statusCounts,
            'upcomingInterviews' => $upcomingInterviews,
            'activitySummary' => $activitySummary,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
