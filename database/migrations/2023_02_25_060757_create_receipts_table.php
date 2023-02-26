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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrolled_student_id');
            $table->date('date');
            $table->unsignedBigInteger('logged_by_user_id');
            $table->timestamps();

            // foreign
            $table->foreign('enrolled_student_id')
                ->references('id')
                ->on('enrolled_students')
                ->onDelete('cascade');

            $table->foreign('logged_by_user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // if exist fees
        if (Schema::hasTable('receipts')) {
            // if exist enrolled_student_id foreign
            if (Schema::hasColumn('receipts', 'enrolled_student_id')) {
                // drop foreign
                Schema::table('receipts', function (Blueprint $table) {
                    $table->dropForeign('receipts_enrolled_student_id_foreign');
                });
            }
        }

        Schema::dropIfExists('receipts');
    }
};
