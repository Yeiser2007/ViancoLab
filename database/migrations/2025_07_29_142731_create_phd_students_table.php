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
        Schema::create('phd_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants');
            $table->string('student_id', 20)->nullable();
            $table->string('cvu', 20)->nullable();
            $table->foreignId('thesis_id')->nullable()->constrained('theses');
            $table->foreignId('program_id')->nullable()->constrained('academic_programs');
            $table->foreignId('research_line_id')->nullable()->constrained('research_lines');
            $table->foreignId('advisor_id')->nullable()->constrained('researchers');
            $table->foreignId('co_advisor_id')->nullable()->constrained('researchers');
            $table->foreignId('external_advisor_id')->nullable()->constrained('external_advisors');
            $table->foreignId('tutor1_id')->nullable()->constrained('researchers');
            $table->foreignId('tutor2_id')->nullable()->constrained('researchers');
            $table->date('enrollment_date');
            $table->date('expected_graduation_date')->nullable();
            $table->date('actual_graduation_date')->nullable();
            $table->enum('status', ['active','on_leave','graduated','withdrawn','dismissed'])->default('active');
            $table->decimal('current_gpa', 3, 2)->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phd_students');
    }
};
