<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationNote extends Model
{
    protected $fillable = [
        'job_application_id',
        'user_id',
        'content',
        'type',
        'reminder_date',
    ];

    protected $casts = [
        'reminder_date' => 'datetime',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
