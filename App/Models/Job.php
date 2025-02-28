<?php

namespace Modules\Jobs\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Items\App\Models\City;
use Modules\Items\App\Models\Country;
use Modules\Jobs\Database\factories\JobFactory;

class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'job_title',
        'job_role',
        'vacancies',
        'years_experience_from',
        'years_experience_to',
        'work_place_type',
        'description',
        'key_responsibilities',
        'qualifications',
        'salary_id',
        'city_id',
        'country_id',
        'job_company_industries_id',
        'job_company_types_id',
        'job_seniority_level_id',
        'job_employment_type_id',
        'created_by_user_id',
        'is_open'
    ];

    protected $table = 'medical_jobs';

    protected static function newFactory(): JobFactory
    {
        return JobFactory::new();
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class, "job_tags");
    }

    public function city():BelongsTo
    {
        return $this->belongsTo(City::class, "city_id", "id");
    }

    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class, "country_id", "id");
    }

    public function company_industry():BelongsTo
    {
        return $this->belongsTo(JobCompanyIndustry::class, "job_company_industries_id", "id");
    }

    public function company_type():BelongsTo
    {
        return $this->belongsTo(JobCompanyType::class, "job_company_types_id", "id");
    }

    public function seniority_level():BelongsTo
    {
        return $this->belongsTo(JobSeniorityLevel::class, "job_seniority_level_id", "id");
    }
    public function employment_type():BelongsTo
    {
        return $this->belongsTo(JobEmploymentType::class, "job_employment_type_id", "id");
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, "created_by_user_id", "id");
    }

    public function salary():BelongsTo
    {
        return $this->belongsTo(Salary::class, "salary_id", "id");
    }



}
