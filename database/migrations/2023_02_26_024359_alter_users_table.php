<?php

use App\Models\User;
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
        // Convert email into username, and add a new position column
        Schema::table('users', function (Blueprint $table) {
            $table->text('username');
            $table->text('position')->nullable();
        });

        // Remove domains from usernames
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            $user->username = explode('@', $user->email)[0];
            $user->save();
        }

        // Remove email column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Return error that this migration can't be reversed.
        throw new \Exception('This migration can\'t be reversed.');
    }
};
