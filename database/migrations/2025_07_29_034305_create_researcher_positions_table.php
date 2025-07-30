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
        Schema::create('researcher_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('researcher_id')->constrained('researchers');
            $table->foreignId('position_id')->constrained('academic_positions');
            $table->foreignId('institution_id')->nullable()->constrained('institutions');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researcher_positions');
    }
};
