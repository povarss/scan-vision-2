<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (User::getRoles() as $role) {
            if(!Role::where('name' , $role)->exists()){
                Role::create(['name' => $role]);
            }
        }
    }
}
