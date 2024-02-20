<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
            $table->float('total_day' ,10, 2)->nullable();
            $table->float('total_price', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_intent_id')->nullable();
            $table->string('currency')->nullable();
            $table->unsignedBigInteger('user_id_paid')->nullable();
            $table->foreign('user_id_paid')->references('id')->on('users');
            $table->unsignedBigInteger('user_id_check_in')->nullable();
            $table->foreign('user_id_check_in')->references('id')->on('users');
            $table->unsignedBigInteger('user_id_check_out')->nullable();
            $table->foreign('user_id_check_out')->references('id')->on('users');
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
