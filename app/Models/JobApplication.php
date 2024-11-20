<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'status_id',
        'job_title',
        'job_description',
        'salary_range',
        'applied_date',
        'job_location',
        'job_type',
        'application_url',
        'requirements',
        'is_remote',
    ];

    protected $casts = [
        'applied_date' => 'date',
        'is_remote' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function status()
    {
        return $this->belongsTo(ApplicationStatus::class, 'status_id');
    }

    public function notes()
    {
        return $this->hasMany(ApplicationNote::class);
    }
}
