<?php
namespace Modules\Jobs\Database\Seeders;

use Illuminate\Database\Seeder;

class JobsDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(JobCompanyIndustrySeeder::class);
        $this->call(JobCompanyTypeSeeder::class);
        $this->call(JobEmploymentTypeSeeder::class);
        $this->call(JobSeniorityLevelSeeder::class);
        $this->call(TagSeeder::class);
    }
}
