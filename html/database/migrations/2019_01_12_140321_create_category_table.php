<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->integer('type');
            $table->integer('parent')->nullable()->default(NULL);
        });

        DB::table('category')->insert(array(
            'id' => 1,
            'name' => 'men',
            'type' => 0
        ));
        DB::table('category')->insert(array(
            'id' => 2,
            'name' => 'women',
            'type' => 0
        ));
        DB::table('category')->insert(array(
            'id' => 3,
            'name' => 'baby',
            'type' => 0
        ));
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category');
    }
}
