<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Xoxoday\Plumapi\Model\xoplum_api_credential;
use Database\Seeders\XoplumApiSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xoplum_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        //default data entry from seeder file
        $seeder = new XoplumApiSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xoplum_api_credentials');
    }
};
