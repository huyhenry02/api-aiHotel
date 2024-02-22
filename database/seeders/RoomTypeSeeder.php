<?php

namespace Database\Seeders;

use App\Modules\RoomType\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen('database/seeders/SeedFiles/room_type.csv', 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            $roomType = new RoomType();
            $roomType->name = $line[0];
            $roomType->code = $line[1];
            $roomType->price = $line[2];
            $roomType->description = $line[3];

            $roomType->save();
        }
        fclose($file);
    }
}
