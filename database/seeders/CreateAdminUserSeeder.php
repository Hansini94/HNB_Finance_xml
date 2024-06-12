<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'hansini',
            'last_name' => 'gunasekara',
            'birthdate' => '1994/06/13',
            'nationality1' => 'Sri Lankan',
            'occupation' => 'Software Engineer',
            'email' => 'hansini@tekgeeks.net',
            'password' => bcrypt('123456'),
            'role_id' => 1
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
