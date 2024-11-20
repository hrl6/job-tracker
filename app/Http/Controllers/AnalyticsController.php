<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $applicationStats = [
            'total' => $user->jobApplications()->count(),
            'active'=> $user->jobApplications()
                ->whereHas('status', fn($query) =>
                    $query->whereNotIn('name', ['Rejected','Withdrawn', 'Accepted'])
                )->count(),
            'this_month'=> $user->jobApplications()
                ->whereMonth('created_at', Carbon::now()->month)
                ->count(),
        ];

        $statusDistribution = $user->jobApplications()
            ->with('status')
            ->get()
            ->groupBy('status.name')
            ->map(fn($items) => $items->count()
        );

        $applicationsOverTime = $user->jobApplications()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($items) => [
                'x' => $items->date,
                'y' => $items->count,
            ]
        );

        $responseRate = $user->jobApplications()
            ->whereHas('status', fn($query) =>
            $query->whereIn('name', ['Phone Screen','Interview','Offer','Accepted'])
            )->count() / max($user->jobApplications()->count(), 1) * 100;

        $commonJobTitles = $user->jobApplications()
            ->selectRaw('job_title, COUNT(*) as count')
            ->groupBy('job_title')
            ->orderByDesc('date')
            ->limit(5)
            ->get();

        return Inertia::render('Analytics/Index', [
            'stats' => $applicationStats,
            'statusDistribution' => $statusDistribution,
            'applicationsOverTime' => $applicationsOverTime,
            'responseRate' => round($responseRate, 1),
            'commonJobTitles'=> $commonJobTitles,
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
