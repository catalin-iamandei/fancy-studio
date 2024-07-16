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
                ->string('phone')
                ->nullable()
                ->after('typology_id');
            $table
                ->longText('writer_relationship')
                ->nullable()
                ->after('phone');
            $table
                ->enum('en_write', [
                    'Deloc',
                    'Putin',
                    'Mediu',
                    'Bine',
                    'Foarte bine',
                ])
                ->after('writer_relationship');
            $table
                ->enum('en_speak', [
                    'Deloc',
                    'Putin',
                    'Mediu',
                    'Bine',
                    'Foarte bine',
                ])
                ->after('en_write');
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
            $table->dropColumn('phone');
            $table->dropColumn('writer_relationship');
            $table->dropColumn('en_write');
            $table->dropColumn('en_speak');
        });
    }
};
