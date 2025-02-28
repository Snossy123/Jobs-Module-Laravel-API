<?php

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
        Schema::create('job_applies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_job_id')->constrained('medical_jobs','id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('CV', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_applies', function (Blueprint $table) {
            $table->dropForeign(['medical_job_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('job_applies');
    }
};
