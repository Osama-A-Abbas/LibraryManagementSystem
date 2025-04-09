<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Create permissions
          Permission::create(['name' => 'view books']);
          Permission::create(['name' => 'create books']);
          Permission::create(['name' => 'edit books']);
          Permission::create(['name' => 'delete books']);
          Permission::create(['name' => 'download books']);
          Permission::create(['name' => 'borrow books']);

          Permission::create(['name' => 'view borrowings']);
          Permission::create(['name' => 'create borrowings']);
          Permission::create(['name' => 'edit borrowings']);
          Permission::create(['name' => 'delete borrowings']);


        //   create admin user and give it all permission
        Role::create(['name' => 'admin']);
        $userAdmin = User::find(1);
        $userAdmin->assignRole('admin');
        $userAdmin->givePermissionTo(Permission::all());

    }
}
