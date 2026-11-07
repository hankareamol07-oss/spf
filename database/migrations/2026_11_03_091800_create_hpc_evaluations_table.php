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
        Schema::create('hpc_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('term')->default(1); // 1 = सत्र १, 2 = सत्र २
            $table->foreignId('evaluator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('peer_student_id')->nullable()->constrained('students')->nullOnDelete();
            
            // 360° Assessment Pillars (JSON)
            $table->json('self_assessment')->nullable();
            $table->json('peer_assessment')->nullable();
            $table->json('teacher_assessment')->nullable();
            $table->json('mentor_guidance')->nullable();
            $table->json('parent_feedback')->nullable();

            // Overall Summary & Status
            $table->string('overall_fln_level', 50)->default('NIPUN_PROFICIENT');
            $table->string('status', 30)->default('completed');

            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id', 'term'], 'hpc_student_year_term_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hpc_evaluations');
    }
};
