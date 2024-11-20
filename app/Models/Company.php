<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'website',
        'location',
        'description',
        'industry',
    ];

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
