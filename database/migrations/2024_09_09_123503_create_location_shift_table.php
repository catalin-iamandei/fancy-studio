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
        Schema::create('location_shift_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('location_id')->unsigned()->nullable();
            $table->bigInteger('shift_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table
                ->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->nullOnDelete()
                ->onUpdate('set null');
            $table
                ->foreign('shift_id')
                ->references('id')
                ->on('shifts')
                ->nullOnDelete()
                ->onUpdate('set null');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->onUpdate('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_shift_user');
    }
};
