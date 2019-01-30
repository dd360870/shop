<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->string('payment_method', 1); #付款方式
            $table->string('payment_status', 1)->default('N'); #付款狀態
            $table->string('delivery_method', 1);
            $table->string('delivery_name');
            $table->string('delivery_address');
            $table->string('delivery_phone', 16);
            $table->string('status');
            $table->integer('total');
            $table->string('delivery_traceID'); #物流追蹤條碼

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('orders');
    }
}
