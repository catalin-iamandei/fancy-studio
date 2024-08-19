<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_site', function (Blueprint $table) {
            $table->bigInteger('site_id')->unsigned()->nullable();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();

            $table
                ->foreign('site_id')
                ->references('id')
                ->on('sites')
                ->nullOnDelete()
                ->onUpdate('set null');
            $table
                ->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->nullOnDelete()
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_site');
    }
};
