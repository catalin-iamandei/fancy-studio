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
            $table->bigInteger('shift_id')->unsigned()->nullable()->after('writer_id');
            $table
                ->foreign('shift_id')
                ->references('id')
                ->on('shifts')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('shift_id');
            $table->dropForeign('employees_shift_id_foreign');
        });
    }
};
