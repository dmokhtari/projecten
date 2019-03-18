<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'title' => 'student',
            'display_title' => 'student'
        ]);
        Role::create([
            'title' => 'admin',
            'display_title' => 'administrator'
        ]);
    }
}
