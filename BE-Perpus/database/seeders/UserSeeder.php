<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleOwner = Role::where('name', 'owner')->first();
        User::create([
            'name' => 'owner',
            'email' => 'owner@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $roleOwner->id,
        ]);
    }
}
