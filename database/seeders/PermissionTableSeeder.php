<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guardName = 'web';

        $modules = include __DIR__ . '/data/Permission_data.php';

        foreach ($modules as $moduleKey => $module) {
            foreach ($module as $sectionKey => $section) {
                foreach ($section as $action) {
                    $route = [
                        'name'       => "$moduleKey.$sectionKey.$action",
                        'module'     => $moduleKey,
                        'section'    => $sectionKey,
                        'action'     => $action,
                        'guard_name' => $guardName,
                    ];
                    Permission::updateOrCreate(['name' => $route['name']], $route);
                }
            }
        }
    }
}
