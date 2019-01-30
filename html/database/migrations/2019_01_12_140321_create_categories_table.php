<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->integer('type')->default(0);
            $table->integer('parent')->nullable()->default(NULL);
        });

        DB::table('categories')->insert(array(
            'id' => 1,
            'name' => 'men',
        ));
        DB::table('categories')->insert(array(
            'id' => 2,
            'name' => 'women',
        ));
        DB::table('categories')->insert(array(
            'id' => 3,
            'name' => 'baby',
        ));
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
