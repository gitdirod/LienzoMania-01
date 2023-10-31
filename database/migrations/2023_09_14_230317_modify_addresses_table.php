<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('envoice'); // Eliminar la columna envoice

            // Agregar la columna type con tipo enum, valor por defecto y posición
            $table->enum('type', ['main', 'envoice', 'send'])
                ->default('main')
                ->after('user_id');

            // Agregar la restricción de unicidad para user_id y type
            $table->unique(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->boolean('envoice')->default(false); // Volver a agregar la columna envoice si necesitas revertir el cambio
            $table->dropUnique(['user_id', 'type']); // Eliminar la restricción de unicidad
            $table->dropColumn('type'); // Eliminar la columna type
        });
    }
}
