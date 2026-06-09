<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'Administrador']);
        $agente = Role::create(['name' => 'Agente Inmobiliario']);
        $asistente = Role::create(['name' => 'Asistente administrativa']);
        $contratista = Role::create(['name' => 'Contratista']);

        Permission::create(['name' => 'panel.admin'])->syncRoles([$admin]);

        Permission::create(['name' => 'inventories.list'])->assignRole($admin,$agente,$asistente);
        Permission::create(['name' => 'inventories.create'])->assignRole($admin,$agente);
        Permission::create(['name' => 'inventories.show'])->assignRole($admin,$agente,$asistente);
        Permission::create(['name' => 'inventories.delete'])->assignRole($admin);

        /* Permisos para la administración de los usuarios */
        Permission::create(['name' => 'users.list'])->assignRole($admin,$asistente);
        Permission::create(['name' => 'users.create'])->assignRole($admin,$asistente);
        Permission::create(['name' => 'users.delete'])->assignRole($admin);

        /* Permisos para la administración de los roles de usuario (Administradores, Profesores) */
        Permission::create(['name' => 'roles.table'])->assignRole($admin);
        Permission::create(['name' => 'roles.create'])->assignRole($admin);
        Permission::create(['name' => 'roles.show'])->assignRole($admin);
        Permission::create(['name' => 'roles.delete'])->assignRole($admin);

        // Permisos para la administración de los tickets PQRS
        Permission::create(['name' => 'tickets.list'])->assignRole($admin,$agente,$asistente,$contratista);
        Permission::create(['name' => 'tickets.create'])->assignRole($admin,$agente,$asistente);
        Permission::create(['name' => 'tickets.show'])->assignRole($admin,$agente,$asistente,$contratista);
        Permission::create(['name' => 'tickets.delete'])->assignRole($admin);
        Permission::create(['name' => 'contractor.admin'])->assignRole($contratista);
    }
}
