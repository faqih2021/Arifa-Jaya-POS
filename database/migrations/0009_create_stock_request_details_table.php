<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_request_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stock_request_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('requested_quantity');
            $table->integer('approved_quantity');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('stock_request_id')->references('id')->on('stock_requests')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_request_details');
    }
};
