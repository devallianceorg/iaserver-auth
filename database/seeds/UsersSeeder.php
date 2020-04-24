<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

	$password = Hash::make('toor');
        $user = User::create(['name' => 'root','email'=>'root@root','password'=>$password]);
        $role = Role::create(['name' => 'superadmin']);

	$user->assignRole($role);
    }
}
