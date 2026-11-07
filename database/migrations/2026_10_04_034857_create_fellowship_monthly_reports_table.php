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
        Schema::create('fellowship_monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('application_id')->default('SPFEDU-0415');
            $table->string('fellow_name')->default('श्री. अमोल किसन हंकारे');
            $table->string('fellow_designation')->default('विषय शिक्षक (विज्ञान)');
            $table->string('school_name')->default('जिल्हा परिषद शाळा नं. २ कुची, ता. कवठेमहांकाळ, जि. सांगली');
            $table->string('address')->default('मु. पो. कुची, ता. कवठेमहांकाळ, जि. सांगली');
            $table->string('qualification')->default('एम.ए. (Edu), बी.ए., बी.एड., डी.एड., B.Sc. (appear)');
            $table->string('email')->default('amolhankare04@gmail.com');
            $table->string('phone')->default('9960967574');
            $table->string('mentor_name')->default('श्री. सोमनाथ वाळके');
            $table->string('project_title')->default('डिजिटल खेळाच्या माध्यमातून शैक्षणिक प्रगती मापन');
            $table->string('month_key')->unique();
            $table->unsignedTinyInteger('month_order')->default(1);
            $table->string('month_name_marathi');
            $table->string('month_name_english');
            $table->date('report_date')->nullable();
            $table->string('place')->default('कुची (सांगली)');
            $table->string('status')->default('draft'); // draft, submitted, approved
            $table->string('week1_2_title')->nullable();
            $table->text('week1_2_description')->nullable();
            $table->string('week3_4_title')->nullable();
            $table->text('week3_4_description')->nullable();
            $table->text('key_outcomes')->nullable();
            $table->text('challenges_and_solutions')->nullable();
            $table->text('next_month_plan')->nullable();
            $table->json('metrics_summary')->nullable();
            $table->json('photos')->nullable();
            $table->text('mentor_remarks')->nullable();
            $table->string('mentor_status')->default('pending'); // pending, approved, revisions_requested
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fellowship_monthly_reports');
    }
};
