<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandiseInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandise_inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('merchandise_id');
            $table->unsignedTinyInteger('color_id');
            $table->unsignedTinyInteger('size_id');
            $table->unsignedInteger('amount')->default(0);
            /*$table->string('product_id', 10)
                ->storedAs('CONCAT(LPAD(merchandise_id, 6, "0"),
                    LPAD(color_id, 2, "0"),
                    LPAD(size_id, 1, "0")
                )')
                ->unique();*/

            $table->foreign('merchandise_id')
                ->references('id')->on('merchandises')
                ->onDelete('restrict');
        });
        //mariadb on heroku not support keyword "stored", so use "persistent"
        DB::statement('ALTER TABLE merchandise_inventory ADD COLUMN `product_id` varchar(10) as (CONCAT(LPAD(merchandise_id, 6, "0"),
            LPAD(color_id, 2, "0"),
            LPAD(size_id, 1, "0"))
        ) persistent ');
        DB::statement('ALTER TABLE merchandise_inventory ADD INDEX `product_id` (`product_id`)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchandise_inventory');
    }
}
