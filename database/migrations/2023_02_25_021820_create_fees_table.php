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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_id');
            $table->text('name');
            $table->decimal('amount', 18, 8);
            $table->boolean('is_required')->default(true);
            $table->timestamps();

            // foreign
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // if exist fees
        if (Schema::hasTable('fees')) {
            // if exist semester_id foreign
            if (Schema::hasColumn('fees', 'semester_id')) {
                // drop foreign
                Schema::table('fees', function (Blueprint $table) {
                    $table->dropForeign('fees_semester_id_foreign');
                });
            }
        }

        Schema::dropIfExists('fees');
    }
};
