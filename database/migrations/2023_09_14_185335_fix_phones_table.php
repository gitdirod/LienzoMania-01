<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phones', function (Blueprint $table) {
            // Verifica si la columna 'type' existe
            if (Schema::hasColumn('phones', 'type')) {
                $table->dropColumn('type'); // Si es así, elimínala
            }

            // Agrega nuevamente la columna 'main', si es que la necesitas
            if (!Schema::hasColumn('phones', 'main')) {
                $table->boolean('main')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
