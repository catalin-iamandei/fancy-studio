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
        Schema::table('employees', function (Blueprint $table) {
            $table
                ->bigInteger('principal_site_id')
                ->unsigned()
                ->nullable()
                ->after('en_speak');
            $table
                ->foreign('principal_site_id')
                ->references('id')
                ->on('sites')
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
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('principal_site_id');
            $table->dropForeign('employees_principal_site_id_foreign');
        });
    }
};
