<?php

namespace Modules\Jobs\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name'];

    public function jobs():BelongsToMany
    {
        return $this->belongsToMany(Job::class, "job_tags");
    }
}
