<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $modules = [
            'users' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'],
            'roles' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'],
            'courses' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'enroll'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        $this->command->info('Permisos creados por módulos: ' . implode(', ', array_keys($modules)));
    }
}