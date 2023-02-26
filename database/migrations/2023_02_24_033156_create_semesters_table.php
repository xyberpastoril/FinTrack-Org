<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->tinyInteger('semester');
            $table->timestamps();
        });

        // Create a semester for the current year and semester.
        DB::table('semesters')->insert([
            'year' => now()->month > 7 ? now()->year : now()->year - 1,
            'semester' => now()->month > 7 ? 1 : 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};
