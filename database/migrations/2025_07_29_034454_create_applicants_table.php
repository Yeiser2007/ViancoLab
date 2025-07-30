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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cohort_id')->nullable()->constrained('cohorts');
            $table->foreignId('research_line_id')->nullable()->constrained('research_lines');
            $table->enum('status', ['pending','under_review','accepted','rejected','waiting_list'])->default('pending');
            $table->date('application_date')->nullable();
            $table->foreignId('institution_id')->nullable()->constrained('institutions');
            $table->string('institutional_email', 100)->nullable();
            $table->string('previous_degree', 100)->nullable();
            $table->decimal('previous_gpa', 3, 2)->nullable();
            $table->string('cv_url', 255)->nullable();
            $table->string('motivation_letter_url', 255)->nullable();
            $table->string('research_proposal_url', 255)->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
