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
        // add middle name column
        Schema::table('students', function (Blueprint $table) {
            $table->text('middle_name')->after('first_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // remove middle name column
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('middle_name');
        });
    }
};
