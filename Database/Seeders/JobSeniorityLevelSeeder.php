<?php

namespace Modules\Jobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobSeniorityLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('job_seniority_levels')->delete();
        DB::table('job_seniority_levels')->insert(array(
            array('name' => 'Intern', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Fresh Graduate', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Junior', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Intermediate', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'First Management Level', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Middle Management Level', 'created_at' => now(), 'updated_at' => now()),
            array('name' => 'Top Management Level', 'created_at' => now(), 'updated_at' => now())
        ));
    }
}
