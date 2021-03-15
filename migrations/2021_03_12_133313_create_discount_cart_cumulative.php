<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCartCumulative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts_cart_cumulative_achieved', function (Blueprint $table) {
            $table->id();
            $table->integer('discount_id');
            $table->integer('user_id');
            $table->tinyInteger('achieved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts_cart_cumulative_achieved');
    }
}
