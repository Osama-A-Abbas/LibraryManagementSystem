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
          Permission::create(['name' => 'view books']); //user
          Permission::create(['name' => 'download books']); //user
          Permission::create(['name' => 'borrow books']); //user
          Permission::create(['name' => 'create books']); //admin
          Permission::create(['name' => 'edit books']); //admin
          Permission::create(['name' => 'delete books']); //admin


          Permission::create(['name' => 'view borrowings']); //admin & user
          Permission::create(['name' => 'create borrowings']); //admin & user
          Permission::create(['name' => 'edit borrowings']); //admin & user
          Permission::create(['name' => 'delete borrowings']); //admin

          Permission::create(['name' => 'view users']); 
          Permission::create(['name' => 'edit users']);
          Permission::create(['name' => 'delete users']);
          Permission::create(['name' => 'manage user roles']);

        //   Permission::create(['name' => 'view genres']);
        //   Permission::create(['name' => 'create genres']);
        //   Permission::create(['name' => 'edit genres']);
        //   Permission::create(['name' => 'delete genres']);

        //   create admin user and give it all permission
        Role::create(['name' => 'admin']);
        $userAdmin = User::find(1);
        $userAdmin->assignRole('admin');
        $userAdmin->givePermissionTo(Permission::all());

    }
}
