<?php

namespace Modules\Jobs\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Jobs\Database\factories\JobApplyFactory;

class JobApply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'medical_job_id',
        'user_id',
        'CV'
    ];

}
