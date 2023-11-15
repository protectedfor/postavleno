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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->string('name')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('first_name')->after('name')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->integer('age')->after('email')->nullable();
            $table->index(['first_name', 'last_name'], 'users_first_name_last_name_index');
            $table->unique(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('password')->change();
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('age');
            $table->dropIndex('users_first_name_last_name_index');
            $table->dropUnique(['first_name', 'last_name']);
        });
    }
};
