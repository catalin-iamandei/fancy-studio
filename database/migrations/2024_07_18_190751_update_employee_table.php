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
        Schema::table('employees', function (Blueprint $table) {
            $table
                ->bigInteger('location_id')
                ->unsigned()
                ->nullable()
                ->after('id');
            $table
                ->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->nullOnDelete()
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employees']);
        });
    }
};
