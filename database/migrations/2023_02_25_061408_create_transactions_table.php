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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('semester_id');
            $table->unsignedBigInteger('receipt_id')->nullable();
            $table->date('date');
            $table->enum('category', [
                'fee',
                'fine', // attendance event
                'other',
            ]);
            $table->enum('type', [
                'income',
                'expense',
                'inbound-transfer',
                'outbound-transfer',
            ]);
            $table->text('description');
            $table->decimal('amount', 18, 8);
            $table->unsignedBigInteger('foreign_key_id');
            $table->unsignedBigInteger('logged_by_user_id');
            $table->timestamps();

            // foreign

            $table->foreign('semester_id')
                ->references('id')
                ->on('semesters')
                ->onDelete('cascade');

            $table->foreign('receipt_id')
                ->references('id')
                ->on('receipts')
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
        if (Schema::hasTable('transactions')) {
            // if exist semester_id foreign
            if (Schema::hasColumn('transactions', 'semester_id')) {
                // drop foreign
                Schema::table('transactions', function (Blueprint $table) {
                    $table->dropForeign('transactions_semester_id_foreign');
                });
            }
        }

        Schema::dropIfExists('transactions');
    }
};
