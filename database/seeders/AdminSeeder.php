<?php

namespace Database\Seeders;

use App\Enums\RoleTypeEnum;
use App\Modules\User\Models\User;
use App\Modules\User\Repositories\Interfaces\UserInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{

    private UserInterface $userRepo;
    public function __construct(
        UserInterface $userRepo,
    )
    {
        $this->userRepo = $userRepo;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/SeedFiles/user.json'));
        $data = json_decode($json);


            User::create([
                'name' => $data->name,
                'role_type' => $data->role_type,
                'address' => $data->address,
                'phone' => $data->phone,
                'email' => $data->email,
                'password' => bcrypt($data->password),
                'age' => $data->age,
                'identification' => $data->identification,
            ]);

    }
}
