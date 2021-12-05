<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create organizations']);
        Permission::create(['name' => 'show organizations']);
        Permission::create(['name' => 'edit organizations']);
        Permission::create(['name' => 'delete organizations']);
        Permission::create(['name' => 'create person']);
        Permission::create(['name' => 'show person']);
        Permission::create(['name' => 'edit person']);
        Permission::create(['name' => 'delete person']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'show user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Account Manager']);
        $role1->givePermissionTo('create organizations');
        $role1->givePermissionTo('show organizations');
        $role1->givePermissionTo('edit organizations');
        $role1->givePermissionTo('delete organizations');
        $role1->givePermissionTo('create person');
        $role1->givePermissionTo('show person');
        $role1->givePermissionTo('edit person');
        $role1->givePermissionTo('delete person');

        $role2 = Role::create(['name' => 'Admin']);
        $role2->givePermissionTo('create user');
        $role2->givePermissionTo('show user');
        $role2->givePermissionTo('edit user');
        $role2->givePermissionTo('delete user');
        $role1->givePermissionTo('show organizations');

        $role3 = Role::create(['name' => 'Super Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = User::factory()->create([
            'name' => 'Antony',
            'email' => 'antony@mail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole($role1);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole($role2);

        $user = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('12345678'),
        ]);
        $user->assignRole($role3);
    }
}
