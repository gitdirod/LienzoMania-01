<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('phones', function (Blueprint $table) {
            // Elimina la columna 'main'
            if (Schema::hasColumn('phones', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('phones', 'main')) {
                $table->dropColumn('main');
            }

            // Agrega la columna 'type' con tipo enum
            $table->enum('type', ['main', 'envoice', 'send'])
                ->default('main')
                ->after('user_id');  // Para colocar la columna después de 'user_id'

            // Añade la restricción única
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
        Schema::table('phones', function (Blueprint $table) {
            // Elimina la restricción única
            $table->dropUnique(['user_id', 'type']);

            // Elimina la columna 'type'
            $table->dropColumn('type');

            // Vuelve a agregar la columna 'main' por si necesitas revertir los cambios
            $table->boolean('main')->default(false);
        });
    }
}
