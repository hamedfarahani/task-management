<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            'name' => 'tribes',
            'email' => 'admin@dev.tribes.work',
            'password' => bcrypt('password'),
            'is_admin' => 1,
        ];
        $user = User::create($user);

        $user->assignRole(RoleEnum::SUPER_ADMIN);
    }
}
