<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('membership_id')->nullable();
            $table->unsignedBigInteger('cashier_user_id');
            $table->string('order_code', 50)->unique();
            $table->date('order_date');
            $table->integer('subtotal');
            $table->integer('total_amount');
            $table->enum('payment_method', ['cash', 'card', 'e-wallet', 'transfer']);
            $table->enum('payment_status', ['pending', 'paid', 'declined']);
            $table->boolean('is_membership_transaction')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('restrict');
            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('set null');
            $table->foreign('cashier_user_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
