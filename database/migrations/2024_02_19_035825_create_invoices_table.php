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
            $table->float('total', 10, 2)->nullable();
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer'])->nullable();
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
