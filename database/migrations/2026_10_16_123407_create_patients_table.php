<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('address')->nullable();
            $table->string('national_id')->nullable();
            $table->string('phone');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth')->nullable();
            $table->string('blood_type')->nullable();
            $table->text('chronic_diseases')->nullable();
            $table->timestamps();
            $table->index(['clinic_id', 'full_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
