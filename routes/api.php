<?php

use Illuminate\Support\Facades\Route;
use Modules\Jobs\App\Http\Controllers\JobApplyController;
use Modules\Jobs\App\Http\Controllers\JobCompanyIndustryController;
use Modules\Jobs\App\Http\Controllers\JobCompanyTypeController;
use Modules\Jobs\App\Http\Controllers\JobController;
use Modules\Jobs\App\Http\Controllers\JobEmploymentTypeController;
use Modules\Jobs\App\Http\Controllers\JobSeniorityLevelController;
use Modules\Jobs\App\Http\Controllers\TagController;
use Modules\Jobs\App\Http\Middleware\JobApplicationsManageMiddleware;
use Modules\Jobs\App\Http\Middleware\JobApplyMiddleware;
use Modules\Jobs\App\Http\Middleware\JobCreationMiddleware;
use Modules\Jobs\App\Http\Middleware\JobManageMiddleware;
use Modules\Jobs\App\Http\Middleware\updateJobMiddleware;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
    | Middleware
    |   Throttle is used to limit the amount of traffic for a given route or group of routes.
    |   It accepts two parameters (max number of requests, that can made in a given number of minutes).
    |   It's default value is 'throttle:60,1' (60 requests, 1 minute), so you can set it to anything.
    |   If the user exceeds this limit, they will receive a 429 Too Many Requests response.
*/

Route::group(['prefix' => 'v1/jobs', 'middleware' => 'auth:sanctum'], function(){
    Route::get('/', [JobController::class, 'show'])->name('jobs');
    Route::get('/topRelated', [JobController::class, 'index'])->name('jobs.topRelated');
    Route::get('/search', [JobController::class, 'search'])->name('search');
    Route::post('/create', [JobController::class, 'store'])->name('jobs.create')->middleware(JobCreationMiddleware::class);
    Route::get('/companyIndustries', [JobCompanyIndustryController::class, 'getCompanyIndustries'])->name('jobs.companyIndustries');
    Route::get('/companyTypes', [JobCompanyTypeController::class, 'getCompanyTypes'])->name('jobs.companyTypes');
    Route::get('/employmentTypes', [JobEmploymentTypeController::class, 'getEmploymentTypes'])->name('jobs.employmentTypes');
    Route::get('/seniorityLevels', [JobSeniorityLevelController::class, 'getSeniorityLevels'])->name('jobs.seniorityLevels');
    Route::get('/tags', [TagController::class, 'getTags'])->name('jobs.tags');
    Route::group(['middleware' => JobManageMiddleware::class], function(){
        Route::put('/edit', [JobController::class, 'edit'])->name('jobs.edit')->middleware(updateJobMiddleware::class);
        Route::delete('/delete', [JobController::class, 'destroy'])->name('jobs.delete');
        Route::put('/changeStatus', [JobController::class, 'changeStatus'])->name('jobs.changeStatus');
    });
    Route::get('/applications', [JobApplyController::class, 'getApplications'])->name('jobs.applications')->middleware(JobApplicationsManageMiddleware::class);
    Route::post('/apply', [JobApplyController::class, 'apply'])->name('jobs.apply')->middleware(JobApplyMiddleware::class);
});
