<?php

namespace Database\Seeders;

use App\Modules\Room\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/SeedFiles/room.json'));
        $data = json_decode($json);

        foreach ($data as $item) {
            Room::create([
                'code' => $item->code,
                'floor' => $item->floor,
                'room_type_id' => $item->room_type_id,
                'hotel_id' => $item->hotel_id,
            ]);
        }
    }
}
