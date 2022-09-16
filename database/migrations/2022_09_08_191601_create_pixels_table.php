<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePixelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pixels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("pixel_id")->nullable();
            $table->unsignedBigInteger("shop_id")->nullable();
            $table->string("type")->nullable();
            $table->string("tag")->nullable();
            $table->string("collection")->nullable();
            $table->text("access_token")->nullable();
            $table->text("test_token")->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('tixels');
    }
}
