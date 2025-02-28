<?php

namespace Modules\Jobs\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Jobs\Database\factories\SalaryFactory;

class Salary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'value',
        'from',
        'to'
    ];
}
