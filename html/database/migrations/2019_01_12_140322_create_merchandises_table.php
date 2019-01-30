<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandises', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name'); #商品名稱
            $table->string('intro'); #商品介紹
            $table->unsignedInteger('category_id');
            $table->integer('price');
            $table->string('photo_path')->default(NULL)->nullable();
            $table->boolean('is_selling')->default(false); #販售狀態
            $table->unsignedTinyInteger('size_min')->default(0);
            $table->unsignedTinyInteger('size_max')->default(0);

            $table->foreign('category_id')
                ->references('id')->on('categories')
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
        Schema::dropIfExists('merchandises');
    }
}
