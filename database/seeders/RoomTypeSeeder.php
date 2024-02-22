<?php

namespace Database\Seeders;

use App\Modules\RoomType\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/SeedFiles/room_type.json'));
        $data = json_decode($json);

        foreach ($data as $item) {
            RoomType::create([
                'name' => $item->name,
                'price' => $item->price,
                'code' => $item->code,
                'description' => $item->description,
            ]);
        }
    }
}
