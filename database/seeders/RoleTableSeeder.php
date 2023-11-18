<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => RoleEnum::ADMIN,
                'area' => RoleEnum::AREA_ADMIN,
            ],
            [
                'name' => RoleEnum::SUPER_ADMIN,
                'area' => RoleEnum::AREA_ADMIN,
            ],
            [
                'name' => RoleEnum::OPERATOR,
                'area' => RoleEnum::AREA_ADMIN,
            ], [
                'name' => RoleEnum::USER,
                'area' => RoleEnum::AREA_USER,
            ]
        ];
        foreach ($roles as $role) {

            $roleModel = Role::updateOrCreate(['name' => $role['name']], [
                'area' => $role['area'],
                'guard_name' => 'web'
            ]);
            if ($roleModel->name == RoleEnum::SUPER_ADMIN) {
                $roleModel->permissions()->sync(Permission::select('id')->get()->pluck('id')->toArray());
            }
        }
    }
}
