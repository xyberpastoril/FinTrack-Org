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
        Schema::create('enrolled_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('degree_program_id');
            $table->unsignedBigInteger('student_id');
            $table->tinyInteger('year_level');
            $table->timestamps();

            $table->unique(['student_id', 'semester_id']);

            // Foreign
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');

            $table->foreign('degree_program_id')
                ->references('id')
                ->on('degree_programs')
                ->onDelete('cascade');

            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });

        // Create all the enrolled students from the students table for semester_id = 1
        // (the first semester in the database).
        foreach(DB::table('students')->get() as $student) {
            DB::table('enrolled_students')->insert([
                'semester_id' => 1,
                'degree_program_id' => $student->degree_program_id,
                'student_id' => $student->id,
                'year_level' => $student->year_level,
                'created_at' => $student->created_at,
                'updated_at' => $student->updated_at,
            ]);
        }

        // Remove degree_program_id and year_level from the students table.
        Schema::table('students', function (Blueprint $table) {
            // drop foreign
            $table->dropForeign('students_degree_program_id_foreign');
            $table->dropColumn('degree_program_id');
            $table->dropColumn('year_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Create an exception for the down() method.
        throw new \Exception('This migration cannot be reversed.');
    }
};
