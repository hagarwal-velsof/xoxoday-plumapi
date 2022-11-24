<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xoplum_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('prefix', 10);
            $table->string('mobile', 20);
            $table->tinyInteger('status');
            $table->string('amount')->nullable();
            $table->integer('product_id');
            $table->integer('quantity');
            $table->string('reference_id')->unique();
            $table->string('plum_order_id')->nullable();
            $table->text('plum_response')->nullable();
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
        Schema::dropIfExists('xoplum_orders');
    }
};
