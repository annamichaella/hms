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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained('wards')->onDelete('cascade');
            $table->string('bed_number', 20);
            $table->enum('bed_type', ['Standard', 'ICU', 'Private', 'Semi-Private'])->default('Standard');
            $table->enum('status', ['Available', 'Occupied', 'Maintenance', 'Reserved'])->default('Available');
            $table->foreignId('patient_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('admission_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
