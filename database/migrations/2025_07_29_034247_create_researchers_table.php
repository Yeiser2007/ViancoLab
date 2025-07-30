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
        
        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('institutional_email', 100)->nullable();
            $table->string('professional_id', 50)->nullable();
            $table->foreignId('sni_level_id')->nullable()->constrained('sni_levels');
            $table->date('sni_start_date')->nullable();
            $table->date('sni_end_date')->nullable();
            $table->foreignId('prodep_status_id')->nullable()->constrained('prodep_status');
            $table->date('prodep_start_date')->nullable();
            $table->date('prodep_end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researchers');
    }
};
