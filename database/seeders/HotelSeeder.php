<?php

namespace Database\Seeders;

use App\Modules\Hotel\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/SeedFiles/hotel.json'));
        $data = json_decode($json);

        foreach ($data as $item) {
            $hotel = Hotel::create([
                'name' => $item->name,
                'address' => $item->address,
                'description' => $item->description,
            ]);
            foreach ($item->room_types as $roomTypeId) {
                $hotel->roomTypes()->attach($roomTypeId);
            }
        }
    }
}
