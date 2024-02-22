<?php

namespace Database\Seeders;

use App\Modules\Service\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/SeedFiles/service.json'));
        $data = json_decode($json);

        foreach ($data as $item) {
            Service::create([
                'name' => $item->name,
                'description' => $item->description,
                'price' => $item->price,
            ]);
        }
    }
}
