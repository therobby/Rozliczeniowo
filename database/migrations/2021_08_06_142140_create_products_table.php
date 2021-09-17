<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->references('id')->on('bills');
            $table->text('name');

            $table->float('original_price');
            $table->string('original_currency', 6)->default('PLN'); // TODO: implement this: http://api.nbp.pl/

            $table->float('price');
            $table->string('currency', 6)->default('PLN'); // TODO: implement this: http://api.nbp.pl/
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
 