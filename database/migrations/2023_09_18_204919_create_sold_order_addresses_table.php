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
        Schema::create('sold_order_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sold_order_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->enum('type', ['main', 'envoice', 'send'])
                ->default('main');

            $table->string('people');
            $table->string('ccruc')
                ->default("");

            $table->string('city');

            $table->string('address');

            $table->string('phone');
            $table->timestamps();
            $table->unique(['sold_order_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sold_order_addresses');
    }
};
