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
        Schema::create('plum_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('client_id',255);
            $table->string('client_secret',255);
            $table->string('refresh_token',255);
            $table->text('access_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plum_api_credentials');
    }
};
