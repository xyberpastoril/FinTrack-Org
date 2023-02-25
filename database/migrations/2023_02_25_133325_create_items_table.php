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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_id');
            $table->text('name');
            $table->decimal('amount', 18, 8);
            $table->timestamps();

            // foreign
            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');
        });

        // Add additional enum entry for transaction category
        DB::statement('ALTER TABLE transactions MODIFY COLUMN category ENUM("fee", "fine", "other", "item") NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // update transactions where category is item to other
        DB::statement('UPDATE transactions SET category = "other" WHERE category = "item"');

        // remove item from enum
        DB::statement('ALTER TABLE transactions MODIFY COLUMN category ENUM("fee", "fine", "other") NOT NULL');

        // if exist fees
        if (Schema::hasTable('items')) {
            // if exist semester_id foreign
            if (Schema::hasColumn('items', 'semester_id')) {
                // drop foreign
                Schema::table('items', function (Blueprint $table) {
                    $table->dropForeign('items_semester_id_foreign');
                });
            }
        }

        Schema::dropIfExists('items');
    }
};
