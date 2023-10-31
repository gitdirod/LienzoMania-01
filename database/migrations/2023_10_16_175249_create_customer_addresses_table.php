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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->enum('type', ['envoice', 'send'])
                ->default('envoice');

            $table->string('ccruc');
            $table->string('people');
            $table->string('phone');
            $table->string('city');
            $table->string('address');

            $table->timestamps();

            // Agregar una restricción única para customer_id y type
            $table->unique(['customer_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
};
