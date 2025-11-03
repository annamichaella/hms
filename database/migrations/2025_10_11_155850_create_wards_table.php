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
        if (Schema::hasTable('wards')) {
            return;
        }
        
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('ward_name', 100);
            $table->enum('ward_type', ['General', 'ICU', 'Emergency', 'Surgery', 'Pediatric', 'Maternity']);
            $table->string('floor', 20);
            $table->integer('capacity')->default(0);
            $table->enum('status', ['Active', 'Maintenance', 'Closed'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
