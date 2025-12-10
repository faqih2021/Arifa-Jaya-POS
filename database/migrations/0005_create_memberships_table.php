<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('registered_by_user_id');
            $table->unsignedBigInteger('registered_at_store_id');
            $table->string('membership_code', 50)->unique();
            $table->string('name');
            $table->text('address');
            $table->string('phone', 20);
            $table->date('joined_at');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('registered_by_user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('registered_at_store_id')->references('id')->on('stores')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('memberships');
    }
};
