<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'administrador' => ['users.*', 'roles.*'],
            'coordinador' => ['users.*', 'roles.*'],
            'sistemas' => ['users.index', 'users.show'],
            'estudiante' => ['courses.index'],
            'aspirante' => ['courses.index'],
            'docente' => ['courses.index'],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            // Asignación con comodines (usando whereLike)
            $permissionNames = [];
            foreach ($permissions as $pattern) {
                if (str_contains($pattern, '*')) {
                    $prefix = str_replace('*', '%', $pattern);
                    $permissionNames = array_merge(
                        $permissionNames,
                        Permission::where('name', 'like', $prefix)->pluck('name')->toArray()
                    );
                } else {
                    $permissionNames[] = $pattern;
                }
            }
            
            $role->syncPermissions(array_unique($permissionNames));
        }

        $this->command->info('Roles con permisos asignados jerárquicamente.');
    }
}