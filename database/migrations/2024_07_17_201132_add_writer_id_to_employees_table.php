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
            $table->bigInteger('writer_id')->unsigned()->nullable()->after('id');
            $table
                ->foreign('writer_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->date('date_start')->after('typology_id');
            $table->string('intern_mail')->after('tips_reaction_proportional')->nullable();
            $table->string('intern_mail_password')->after('intern_mail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['writer_id']);
            $table->dropColumn(['writer_id', 'date_start', 'intern_mail', 'intern_mail_password']);
        });
    }
};
