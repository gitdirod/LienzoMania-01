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
        Schema::table('addresses', function (Blueprint $table) {
            // Remove the foreign key constraint
            $table->string('phone');
            $table->dropForeign(['phone_id']);

            // Remove the "phone_id" column
            $table->dropColumn('phone_id');
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
            // Here you can add the code to re-add the "phone_id" column and foreign key if needed
            $table->dropColumn('phone'); // Eliminar la columna type
            $table->unsignedBigInteger('phone_id');
            $table->foreign('phone_id')->references('id')->on('phones');
        });
    }
};
