<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Permisos para centros
        Permission::create(['name' => 'centros_create']);
        Permission::create(['name' => 'centros_delete']);
        Permission::create(['name' => 'centros_view']);
        Permission::create(['name' => 'centros_edit']);

        // Permisos para cursos
        Permission::create(['name' => 'cursos_create']);
        Permission::create(['name' => 'cursos_delete']);
        Permission::create(['name' => 'cursos_view']);
        Permission::create(['name' => 'cursos_edit']);

        // Permisos para inscripciones
        Permission::create(['name' => 'inscripciones_create']);
        Permission::create(['name' => 'inscripciones_delete']);
        Permission::create(['name' => 'inscripciones_view']);
        Permission::create(['name' => 'inscripciones_edit']);

        // Permisos para personas
        Permission::create(['name' => 'personas_create']);
        Permission::create(['name' => 'personas_delete']);
        Permission::create(['name' => 'personas_view']);
        Permission::create(['name' => 'personas_edit']);

        $role = Role::create(['name' => 'admin_salud']);
        $role->givePermissionTo(['centros_view']);

        $role = Role::create(['name' => 'admin_rrhh']);
        $role->givePermissionTo(['centros_view', 'inscripciones_view']);

        $role = Role::create(['name' => 'admin_edu']);
        $role->givePermissionTo(['centros_view', 'inscripciones_view']);

        $role = Role::create(['name' => 'superadmin']);
        $role->givePermissionTo(Permission::all());
    }
}
