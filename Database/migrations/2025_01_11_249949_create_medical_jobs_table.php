<?php

use App\Enums\WorkPlaceType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title', 255);
            $table->string('job_role', 255);
            $table->unsignedInteger('vacancies');
            $table->unsignedTinyInteger('years_experience_from');
            $table->unsignedTinyInteger('years_experience_to')->nullable();
            $table->enum("work_place_type", array_column(WorkPlaceType::cases(), 'value'));
            $table->text('description');
            $table->text('key_responsibilities');
            $table->text('qualifications');
            $table->boolean('is_open')->default(true);
            $table->timestamps();

            // Foreign keys with cascading rules
            $table->foreignId('salary_id')->nullable()->references('id')->on('salaries')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('job_company_industries_id')->nullable()->constrained('job_company_industries')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('job_company_types_id')->nullable()->constrained('job_company_types')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('job_seniority_level_id')->nullable()->constrained('job_seniority_levels')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('job_employment_type_id')->nullable()->constrained('job_employment_types')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('created_by_user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_jobs');
    }
};
