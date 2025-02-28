<?php

namespace Modules\Jobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_company_types')->delete();
        DB::table('job_company_types')->insert(
            [
                ['name' => 'Trading', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Events', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Training and Education', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Research', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Consultation', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Maintenance', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Finance & Leasing', 'created_at' => now(), 'updated_at' => now()],
            ]
        );
    }
}
