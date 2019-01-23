<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('merchandise_id');
            $table->integer('amount');
            $table->integer('price');

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('restrict');
            
            $table->foreign('merchandise_id')
                ->references('id')->on('merchandises')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
