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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('degree_program_id');
            
            $table->text('id_number'); // encrypted
            $table->text('last_name'); // encrypted
            $table->text('first_name'); // encrypted
            $table->tinyInteger('year_level');
            $table->timestamps();

            // Foreign
            $table->foreign('degree_program_id')
                ->references('id')
                ->on('degree_programs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
