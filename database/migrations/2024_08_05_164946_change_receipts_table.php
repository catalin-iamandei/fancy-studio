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
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign('payments_site_id_foreign');
            $table->dropColumn('site_id');
            $table->json('sites')->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->foreignId('site_id')->constrained();
            $table->dropColumn('sites');
        });
    }
};
