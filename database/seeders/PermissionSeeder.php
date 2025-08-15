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

            // Student Module
            'student_progress' => ['index', 'show'],
            'student_documents' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'show'],
            'student_protocol' => ['index', 'create', 'store', 'edit', 'update', 'show'],
            'student_thesis' => ['index', 'create', 'store', 'edit', 'update', 'show'],
            'student_scholarship' => ['index', 'show'],

            // Advisor Module
            'advisor_students' => ['index', 'show', 'assign'],
            'advisor_reviews' => ['index', 'create', 'store', 'edit', 'update', 'show'],
            'advisor_calendar' => ['index', 'create', 'store', 'edit', 'update', 'destroy'],

            // Administration Module
            'admin_generations' => ['index', 'create', 'store', 'edit', 'update', 'destroy'],
            'admin_scholarships' => ['index', 'create', 'store', 'edit', 'update', 'destroy', 'assign'],
            'admin_research_lines' => ['index', 'create', 'store', 'edit', 'update', 'destroy'],
            'admin_reports' => ['index', 'generate', 'export'],

            // Communication
            'messages' => ['index', 'create', 'store', 'show', 'destroy'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$module.$action"]);
            }
        }

        $this->command->info('Permisos creados por módulos: ' . implode(', ', array_keys($modules)));
    }
}