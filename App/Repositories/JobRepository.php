<?php

namespace Modules\Jobs\App\Repositories;

use App\Enums\EmploymentType;
use App\Enums\SalaryType;
use App\Models\UserInfo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Items\App\Models\City;
use Modules\Items\App\Models\Country;
use Modules\Jobs\App\Http\Requests\JobListingRequest;
use Modules\Jobs\App\Http\Requests\JobRequest;
use Modules\Jobs\App\Http\Requests\JobSearchRequest;
use Modules\Jobs\App\Http\Requests\JobStatusRequest;
use Modules\Jobs\App\Models\Job;
use Modules\Jobs\App\Models\Salary;
use Modules\Jobs\App\resources\JobProfileResource;
use Modules\Jobs\App\resources\JobResource;
use Modules\Jobs\App\Interfaces\JobRepositoryInterface;
use Modules\Jobs\App\Models\JobCompanyIndustry;
use Modules\Jobs\App\Models\JobCompanyType;
use Modules\Jobs\App\Models\JobEmploymentType;
use Modules\Jobs\App\Models\JobSeniorityLevel;
use Modules\Jobs\App\Models\Tag;
use Modules\Jobs\App\resources\JobStatusResource;

class JobRepository implements JobRepositoryInterface
{

    public function __construct(protected Job $jobModel)
    {
    }

    public function index(JobListingRequest $request): LengthAwarePaginator
    {
        try {
            [$paginate, $sort, $speciality] = $this->getParams($request);

            $jobQuery = $this->jobModel->newQuery();

            // Apply sorting
            $orderByDirection = $sort === 'ascending' ? 'asc' : 'desc';
            $jobQuery->orderBy('created_at', $orderByDirection);

            // Filter by speciality if specified
            if ($speciality !== 'unspecified') {
                $jobCompanyIndustryId = JobCompanyIndustry::where('name', $speciality)->value('id');

                // Add filter only if associated jobs exist
                if ($jobCompanyIndustryId && $this->jobModel->where('job_company_industries_id', $jobCompanyIndustryId)->count()) {
                    $jobQuery->where('job_company_industries_id', $jobCompanyIndustryId);
                }
            }

            // Fetch and paginate jobs
            $jobs = $jobQuery->paginate($paginate);

            // Transform jobs with resource collection
            $jobs->setCollection(collect(JobProfileResource::collection($jobs)));

            return $jobs;
        } catch (QueryException $e) {
            Log::error("Database Error while fetching jobs: {$e->getMessage()}", ['request' => $request->all()]);
            throw new \Exception("A database error occurred while fetching jobs. Please try again later.");
        } catch (\Exception $e) {
            Log::error("Unexpected error while fetching jobs: {$e->getMessage()}", ['request' => $request->all()]);
            throw new \Exception("An unexpected error occurred while fetching jobs. Please contact your support administrator.");
        }
    }

    public function find(int $id): JobProfileResource
    {
        try {
            $job = $this->jobModel->findOrFail($id);
            return new JobProfileResource($job);
        } catch (ModelNotFoundException $e) {
            Log::warning("Job not found", ['Job ID' => $id]);
            throw new \Exception("Job not found with ID: {$id}. Please check the ID and try again.");
        } catch (QueryException $e) {
            Log::error("Database query error", ['Job ID' => $id, 'Error' => $e->getMessage()]);
            throw new \Exception("A database error occurred while fetching the job profile. Please try again later.");
        } catch (\Exception $e) {
            Log::critical("Unexpected error", ['Job ID' => $id, 'Error' => $e->getMessage()]);
            throw new \Exception("An unexpected error occurred. Please contact your support administrator.");
        }
    }

    public function create(JobRequest $request): JobResource
    {
        try {
            DB::beginTransaction();

            $job = $this->jobModel::create($this->formatJobRequest($request));
            $tagIDs = $this->getTagsIDs($request->tags);
            $job->tags()->sync($tagIDs);

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error(
                "Database error while creating job: {$e->getMessage()}",
                ['request' => $request->all()]
            );
            throw new \Exception("A database error occurred, please try again later.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(
                "Unexpected error while creating job: {$e->getMessage()}",
                ['request' => $request->all()]
            );
            throw new \Exception('An unexpected error occurred, please contact your support administrator');
        }

        return JobResource::make($job);
    }

    public function search(JobSearchRequest $request): LengthAwarePaginator
    {
        try {
            $jobQuery = $this->jobModel->newQuery();
            $prepareSearchParams = $this->getSearchParams($request);

            if (isset($prepareSearchParams['job_title'])) {
                $jobQuery->where('job_title', 'LIKE', '%' . $prepareSearchParams['job_title'] . '%');
                unset($prepareSearchParams['job_title']);
            }
            if (isset($prepareSearchParams['years_experience_from'])) {
                $jobQuery->where('years_experience_from', '>=', $prepareSearchParams['years_experience_from']);
                unset($prepareSearchParams['years_experience_from']);
            }
            if (isset($prepareSearchParams['years_experience_to'])) {
                $jobQuery->where(function ($query) use ($prepareSearchParams) {
                    $query->where('years_experience_to', '<=', $prepareSearchParams['years_experience_to'])
                    ->orWhereNull('years_experience_to');
                });
                unset($prepareSearchParams['years_experience_to']);
            }
            if(isset($prepareSearchParams['job_employment_type_ids'])){
                $jobQuery->whereIn('job_employment_type_id', $prepareSearchParams['job_employment_type_ids']);
                unset($prepareSearchParams['job_employment_type_ids']);
            }
            if(isset($prepareSearchParams['job_company_industries_ids'])){
                $jobQuery->whereIn('job_company_industries_id', $prepareSearchParams['job_company_industries_ids']);
                unset($prepareSearchParams['job_company_industries_ids']);
            }
            if(isset($prepareSearchParams['job_seniority_level_ids'])){
                $jobQuery->whereIn('job_seniority_level_id', $prepareSearchParams['job_seniority_level_ids']);
                unset($prepareSearchParams['job_seniority_level_ids']);
            }
            if(isset($prepareSearchParams['work_place_types'])){
                $jobQuery->whereIn('work_place_type', $prepareSearchParams['work_place_types']);
                unset($prepareSearchParams['work_place_types']);
            }
            $orderByDirection = $prepareSearchParams['sort'] === 'ascending' ? 'asc' : 'desc';
            unset($prepareSearchParams['sort']);
            $paginate = $prepareSearchParams['paginate']??21;
            unset($prepareSearchParams['paginate']);
            $jobQuery = $jobQuery->where($prepareSearchParams);
            // Apply sorting
            $jobQuery->orderBy('created_at', $orderByDirection);
            // Fetch and paginate jobs
            $jobs = $jobQuery->paginate($paginate);

            // Transform jobs with resource collection
            $jobs->setCollection(collect(JobProfileResource::collection($jobs)));

            return $jobs;
        } catch (QueryException $e) {
            Log::error("Database Error while fetching jobs: {$e->getMessage()}", ['request' => $request->all()]);
            throw new \Exception("A database error occurred while fetching jobs. Please try again later.");
        } catch (\Exception $e) {
            Log::error("Unexpected error while fetching jobs: {$e->getMessage()}", ['request' => $request->all()]);
            throw new \Exception("An unexpected error occurred while fetching jobs. Please contact your support administrator.");
        }
    }

    public function update(JobRequest $request): JobProfileResource
    {
        try {
            DB::beginTransaction();

            // Find the job by ID
            $job = $this->jobModel->findOrFail($request->id);

            // Update the job using the formatted request data
            $job->update($this->formatJobRequest($request));

            // Sync the tags for the job
            $tagIDs = $this->getTagsIDs($request->tags);
            $job->tags()->sync($tagIDs);

            DB::commit();
            return new JobProfileResource($job);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning("Job not found", ['Request' => $request]);
            throw new \Exception("Job not found with ID: {$request->id}. Please check the ID and try again.");
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("Database query error", ['Job Request' => $request, 'Error' => $e->getMessage()]);
            throw new \Exception("A database error occurred while updating the job. Please try again later.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::critical("Unexpected error", ['Job Request' => $request, 'Error' => $e->getMessage()]);
            throw new \Exception("An unexpected error occurred. Please contact your support administrator.");
        }
    }

    public function delete(int $id):JobProfileResource
    {
        try {
            // Find the job by ID
            $job = $this->jobModel->findOrFail($id);

            // Delete the job
            $job->delete();

            return new JobProfileResource($job);
        } catch (ModelNotFoundException $e) {
            Log::warning("Job not found", ['Job ID' => $id]);
            throw new \Exception("Job not found with ID: {$id}. Please check the ID and try again.");
        } catch (QueryException $e) {
            Log::error("Database query error", ['Job ID' => $id, 'Error' => $e->getMessage()]);
            throw new \Exception("A database error occurred while Deleting the job. Please try again later.");
        } catch (\Exception $e) {
            Log::critical("Unexpected error", ['Job ID' => $id, 'Error' => $e->getMessage()]);
            throw new \Exception("An unexpected error occurred. Please contact your support administrator.");
        }
    }

    public function updateStatus(JobStatusRequest $request):JobStatusResource
    {
        try {
            // Find the job by ID
            $job = $this->jobModel->findOrFail($request->id);

            // Update the job status
            $job->is_open = $request->job_status ?? true;
            $job->save();

            return new JobStatusResource($job);
        } catch (ModelNotFoundException $e) {
            Log::warning("Job not found", ['Request' => $request]);
            throw new \Exception("Job not found with ID: {$request->id}. Please check the ID and try again.");
        } catch (QueryException $e) {
            Log::error("Database query error", ['Job Request' => $request, 'Error' => $e->getMessage()]);
            throw new \Exception("A database error occurred while updating the job. Please try again later.");
        } catch (\Exception $e) {
            Log::critical("Unexpected error", ['Job Request' => $request, 'Error' => $e->getMessage()]);
            throw new \Exception("An unexpected error occurred. Please contact your support administrator.");
        }
    }



    // Helper functions ------------------------------------------------

    private function getSearchParams(JobSearchRequest $request): array
    {
        $searchParams = [
            "job_title" => $request->job_title ?? "",
            "city_id" => $request->job_location['city_id'] ?? -1,
            "country_id" => $request->job_location['country_id'] ?? -1,
            "job_employment_type_ids" => $this->getEmploymentIDs($request->job_employment ?? []) ?? [],
            "years_experience_from" => $request->years_experience['from'] ?? -1,
            "years_experience_to" => $request->years_experience['to'] ?? -1,
            "job_company_industries_ids" => $request->job_company_industry ?? [],
            "job_seniority_level_ids" => $request->job_seniority_level ?? [],
            "work_place_types" => $request->work_place_type ?? [],
            "paginate" => $request->paginate ?? 21,
            "sort" => $request->sort ?? "descending"
        ];
        foreach ($searchParams as $param => $value) {
            if ($value === -1 || $value === "" || $value === []) {
                unset($searchParams[$param]);
            }
        }

        return $searchParams;
    }

    private function getEmploymentIDs(array $types): array
    {
        $query = JobEmploymentType::query();
        $query = $query->whereIn("type", $types)->pluck("id");
        return $query->toArray() ?? [];
    }

    private function getParams(JobListingRequest $request): array
    {
        $speciality = UserInfo::where('user_id', Auth::id())
        ->where('attribute_name', 'specialists')
        ->first()
        ->value ?? 'unspecified';
        return [
            (int) ($request->input('paginate') ?? "3"),
            $request->input('sort') ?? 'descending',
            $speciality,
        ];
    }

    private function formatJobRequest(JobRequest $request): array
    {
        return [
            "job_title" => $request->job_title,
            "job_role" => $request->job_role,
            "vacancies" => $request->vacancies,
            "years_experience_from" => $request->years_experience_from,
            "years_experience_to" => $request->years_experience_to ?? null,
            "work_place_type" => $request->work_place_type,
            "description" => $request->description,
            "key_responsibilities" => $request->key_responsibilities,
            "qualifications" => $request->qualifications,
            "salary_id" => $this->getSalaryID($request->salary),
            "city_id" => $request->city_id,
            "country_id" => $request->country_id,
            "job_company_industries_id" => $this->getJobCompanyIndustryID($request->job_company_industry),
            "job_company_types_id" => $this->getJobCompanyTypeID($request->job_company_type),
            "job_seniority_level_id" => $this->getJobSeniorityLevelID($request->job_seniority_level),
            "job_employment_type_id" => $this->getJobemploymentTypeID($request->job_employment['type'], $request->job_employment['working_hours'] ?? null),
            "created_by_user_id" => Auth::id()
        ];
    }

    private function getTagsIDs(array $tagsValues): array
    {
        $tagIDs = [];
        foreach ($tagsValues as $tagValue) {
            $tag = Tag::firstOrCreate(['name' => $tagValue]);
            $tagIDs[] = $tag->id;
        }
        return $tagIDs;
    }

    private function getJobCompanyIndustryID(string $industry): int
    {
        return JobCompanyIndustry::firstOrCreate(['name' => $industry])->id;
    }

    private function getJobCompanyTypeID(string $type): int
    {
        return JobCompanyType::firstOrCreate(['name' => $type])->id;
    }

    private function getJobSeniorityLevelID(string $seniorLevel): int
    {
        return JobSeniorityLevel::firstOrCreate(['name' => $seniorLevel])->id;
    }

    private function getJobemploymentTypeID(string $employmentType, ?float $workingHours): int
    {
        if ($employmentType != EmploymentType::WorkingHours->value) {
            $workingHours = null;
        }

        // Check if the record already exists
        $jobEmploymentType = JobEmploymentType::where([
            'type' => $employmentType,
            'value' => $workingHours,
        ])->first();

        if ($jobEmploymentType) {
            return $jobEmploymentType->id;
        }

        // Create a new record if it doesn't exist
        $newJobEmploymentType = JobEmploymentType::create([
            'type' => $employmentType,
            'value' => $workingHours,
        ]);

        return $newJobEmploymentType->id;
    }

    private function getSalaryID(array $salary): int
    {
        switch ($salary['type']) {
            case SalaryType::Static ->value:
                $salary['from'] = $salary['to'] = 0;
                break;
            case SalaryType::Range->value:
                $salary['value'] = 0;
                break;
            case SalaryType::Sentence->value:
                $salary['value'] = $salary['from'] = $salary['to'] = 0;
                break;
        }
        $data = [
            "type" => $salary['type'],
            "value" => $salary['value'],
            "from" => $salary['from'],
            "to" => $salary['to']
        ];
        return Salary::create($data)->id;
    }

    public function checkCityInCountry(int $countryId, int $cityId): void
    {
        $city = City::with('country')->find($cityId);

        if (!$city || $city->country->id !== $countryId) {
            $countryName = Country::find($countryId)?->name ?? "Country ID {$countryId}";
            $cityName = $city?->name ?? "City ID {$cityId}";
            Log::error("{$cityName} city is not in {$countryName}.");
            throw new \Exception("{$cityName} city is not in {$countryName}.");
        }
    }
}
