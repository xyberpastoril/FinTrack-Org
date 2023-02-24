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
        // Create a new table for attendance_events
        Schema::create('attendance_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('name');
            $table->date('date');
            $table->enum('status', ['closed', 'timein', 'timeout'])->default('closed');
            $table->enum('required_logs', ['0', '1', '2'])->default('2');
            $table->decimal('fines_amount_per_log', 18, 8);
            $table->timestamps();

            // Foreign
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
        });

        // Copy all the data from the events table to the attendance_events table.
        foreach(DB::table('events')->get() as $event) {
            DB::table('attendance_events')->insert([
                'event_id' => $event->id,
                'name' => $event->name,
                'date' => $event->date,
                'status' => $event->status,
                'required_logs' => '2',
                'fines_amount_per_log' => 0,
                'created_at' => $event->created_at,
                'updated_at' => $event->updated_at,
            ]);
        }

        // Add semester_id to the events table.
        // Add date_to to the events table.
        // Rename date to date_from to the events table.
        // Remove status from the events table.
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_id');
            $table->renameColumn('date', 'date_from');
            $table->date('date_to')->nullable();
            $table->dropColumn('status');
        });

        // Set the semester_id for all the events to 1 (the first semester in the database).
        // Set the date_to for all the events to the same as date_from.
        foreach(DB::table('events')->get() as $event) {
            DB::table('events')->where('id', $event->id)->update([
                'semester_id' => 1,
                'date_to' => $event->date_from,
            ]);
        }

        // Set semester_id as foreign
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
        });

        // Rename the event_logs table to attendance_event_logs
        Schema::rename('event_logs', 'attendance_event_logs');

        // Rename the event_id column to attendance_event_id in the attendance_event_logs table.
        // Rename the student_id column to enrolled_student_id in the attendance_event_logs table.
        Schema::table('attendance_event_logs', function (Blueprint $table) {
            $table->renameColumn('event_id', 'attendance_event_id');
            $table->renameColumn('student_id', 'enrolled_student_id');
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
