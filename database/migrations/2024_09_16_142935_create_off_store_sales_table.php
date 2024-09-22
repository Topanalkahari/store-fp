<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffStoreSalesTable extends Migration
{
    public function up()
    {
        Schema::create('off_store_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->timestamps();

            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('off_store_sales');
    }
}