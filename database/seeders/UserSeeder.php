<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run()
    {
        // Usuario Administrador
        $admin = User::create([
            'name' => 'Admin Principal',
            'first_name' => 'Admin',
            'last_name' => 'Principal',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('administrador');

        // Usuario de Sistemas
        $sistemas = User::create([
            'name' => 'Técnico de Sistemas',
            'first_name' => 'Técnico',
            'last_name' => 'de Sistemas',
            'email' => 'sistemas@example.com',
            'password' => Hash::make('password'),
        ]);
        $sistemas->assignRole('sistemas');

        // Coordinador Académico
        $coordinador = User::create([
            'name' => 'Coordinador Académico',
            'first_name' => 'Coordinador',
            'last_name' => 'Academico',
            'email' => 'coordinador@example.com',
            'password' => Hash::make('password'),
        ]);
        $coordinador->assignRole('coordinador');

        // Docente
        $docente = User::create([
            'name' => 'Profesor Ejemplo',
            'first_name' => 'Profesor',
            'last_name' => 'Ejemplo',
            'email' => 'docente@example.com',
            'password' => Hash::make('password'),
        ]);
        $docente->assignRole('docente');

        // Estudiante
        $estudiante = User::create([
            'name' => 'Estudiante Demo',
            'first_name' => 'Estudiante',
            'last_name' => 'Demo',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
        ]);
        $estudiante->assignRole('estudiante');

        // Aspirante
        $aspirante = User::create([
            'name' => 'Aspirante Nuevo',
            'first_name' => 'Aspirante',
            'last_name' => 'Nuevo',
            'email' => 'aspirante@example.com',
            'password' => Hash::make('password'),
        ]);
        $aspirante->assignRole('aspirante');

        $this->command->info('Usuarios de prueba creados correctamente.');
    }
}
