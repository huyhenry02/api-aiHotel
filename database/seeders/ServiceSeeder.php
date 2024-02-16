<?php

namespace Database\Seeders;

use App\Modules\Service\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $file = fopen('database/seeders/SeedFiles/service.csv', 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            $service = new Service();
            $service->name = $line[0];
            $service->description = $line[1];
            $service->price = $line[2];

            $service->save();
        }
        fclose($file);
    }
}
