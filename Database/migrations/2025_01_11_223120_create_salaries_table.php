<?php

use App\Enums\SalaryType;
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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', array_column(SalaryType::cases(), 'value'));
            $table->decimal('value', 10, 2)->nullable(); // For static salary
            $table->decimal('from', 10, 2)->nullable(); // For range minimum
            $table->decimal('to', 10, 2)->nullable(); // For range maximum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
