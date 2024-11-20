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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('status_id')->constrained('application_statuses');
            $table->string('job_title');
            $table->text('job_description')->nullable();
            $table->string('salary_range')->nullable();
            $table->date('applied_date');
            $table->string('job_location')->nullable();
            $table->string('job_type')->nullable();
            $table->string('application_url')->nullable();
            $table->text('requirements')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
