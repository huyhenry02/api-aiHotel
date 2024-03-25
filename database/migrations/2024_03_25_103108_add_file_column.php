<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->longText('file')->nullable()->after('price');
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->longText('file')->nullable()->after('description');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->longText('file')->nullable()->after('age');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn('file');
        });

        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('file');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
};
