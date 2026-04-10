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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_user_id')->constrained('users')->onDelete('cascade');
            $table->string('hospital_location', 255)->nullable();
            $table->string('hospital_name', 150)->nullable();
            $table->string('chamber_location', 255)->nullable();
            $table->decimal('visiting_fee', 10, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
