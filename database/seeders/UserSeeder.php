<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'John Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin@admin.com'),
        ]);
        $user->assignRole(User::ROLE_ADMIN);
    }
}
