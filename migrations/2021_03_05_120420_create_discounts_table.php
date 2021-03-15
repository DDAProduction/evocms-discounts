<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('title',100);
            $table->string('type',20);

            $table->timestamp('rule_period_from')->nullable();
            $table->timestamp('rule_period_to')->nullable();

            $table->string('rule_user_groups')->nullable();

            $table->text('apply');
            $table->float('discount_value');
            $table->string('discount_type',20);
            $table->tinyInteger('active');
            $table->tinyInteger('exclude_sales');
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
        Schema::dropIfExists('discounts');
    }
}
