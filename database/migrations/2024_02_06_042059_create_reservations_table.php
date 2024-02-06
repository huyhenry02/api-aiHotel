<?php

use App\Enums\ReservationStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->enum('status', ReservationStatusEnum::values());
            $table->string('reject_reason')->nullable();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('amount_person')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
}
