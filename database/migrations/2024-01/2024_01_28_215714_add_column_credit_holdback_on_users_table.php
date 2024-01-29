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
    public function up(): void
    {
        Schema::table(
            'users',
            function (Blueprint $table) {
                $table->bigInteger('credit_holdback')->default(0);
                $table->bigInteger('credit')->default(0)->change();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'users',
            function (Blueprint $table) {
                $table->dropColumn('credit_holdback');
            }
        );
    }
};
