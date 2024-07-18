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
                ->time('check_in')
                ->nullable()
                ->after('principal_site_id');
            $table
                ->longText('attitude')
                ->nullable()
                ->after('check_in');
            $table
                ->smallInteger('write')
                ->nullable()
                ->after('attitude');
            $table
                ->smallInteger('tips_reaction_speed')
                ->nullable()
                ->after('write');
            $table
                ->smallInteger('tips_reaction_proportional')
                ->nullable()
                ->after('tips_reaction_speed');
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
            $table->dropColumn('check_in');
            $table->dropColumn('attitude');
            $table->dropColumn('write');
            $table->dropColumn('tips_reaction_speed');
            $table->dropColumn('tips_reaction_proportional');
        });
    }
};
