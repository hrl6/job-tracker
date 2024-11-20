<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
        'order',
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'status_id');
    }
}
