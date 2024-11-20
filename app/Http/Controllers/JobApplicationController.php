<?php

namespace App\Http\Controllers;

use App\Models\ApplicationStatus;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobApplications = JobApplication::with('company', 'status')
            ->where('status_id', auth()->id())
            ->latest()
            ->get();

        $statuses = ApplicationStatus::orderBy('order')->get();

        return Inertia::render('Jobs/Index', [
            'jobApplications'=> $jobApplications,
            'statuses'=> $statuses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = ApplicationStatus::orderBy('order')->get();
        $companies = Company::orderBy('name')->get();

        return Inertia::render('Jobs/Create', [
            'statuses' => $statuses,
            'companies' => $companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'status_id' => 'required|exists:application_statuses,id',
            'job_description' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'applied_date' => 'required|date',
            'job_location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:255',
            'application_url' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',
            'is_remote' => 'boolean'
        ]);

        $validated['user_id'] = auth()->id();

        JobApplication::create($validated);

        return redirect()->route('jobs.index')
            ->with('success', 'Job application created successfully.');
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
    public function edit(JobApplication $job)
    {
        $statuses = ApplicationStatus::orderBy('order')->get();
        $companies = Company::orderBy('name')->get();

        return Inertia::render('Jobs/Edit', [
            'jobApplication' => $job->load(['company', 'status']),
            'statuses' => $statuses,
            'companies' => $companies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $job)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'status_id' => 'required|exists:application_statuses,id',
            'job_description' => 'nullable|string',
            'salary_range' => 'nullable|string|max:255',
            'applied_date' => 'required|date',
            'job_location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|max:255',
            'application_url' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',
            'is_remote' => 'boolean'
        ]);

        $job->update($validated);

        return redirect()->route('jobs.index')
            ->with('success', 'Job application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $job)
    {
        $job->delete();

        return redirect()->route('jobs.index')
            ->with('success', 'Job application deleted successfully.');
    }
}
