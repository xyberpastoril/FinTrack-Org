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
        // Change `abbr` datatype from `string` to `text`, then `name` from `string` to `text`
        Schema::table('degree_programs', function (Blueprint $table) {
            $table->text('abbr')->change();
            $table->text('name')->change();
        });

        // run command php artisan encryptable:encryptModel 'App\Model\DegreeProgram'
        // to encrypt the data in the database
        \Illuminate\Support\Facades\Artisan::call('encryptable:encryptModel', ['model' => 'App\Models\DegreeProgram']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // decrypt the data in the database
        \Illuminate\Support\Facades\Artisan::call('encryptable:decryptModel', ['model' => 'App\Models\DegreeProgram']);

        // Change `abbr` datatype from `text` to `string`, then `name` from `text` to `string`
        Schema::table('degree_programs', function (Blueprint $table) {
            $table->string('abbr')->change();
            $table->string('name')->change();
        });
    }
};
