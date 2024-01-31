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
        try {
            DB::beginTransaction();
            $this->userRepo->create([
                'name' => 'Super Admin',
                'role_type' => RoleTypeEnum::ADMIN,
                'address' => 'Ai-Hotel',
                'phone' => '0123456789',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('Admin@123'),
                'identification' => '123456789',
                'age' => 30,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage() . "\n";
        }
    }
}
