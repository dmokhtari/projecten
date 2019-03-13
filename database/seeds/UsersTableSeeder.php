<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = User::create([
            'forename' => 'Davood',
            'surname' => 'Mokhtari',
            'email' => 'davidmokhtari@hotmail.com',
            'password' => Hash::make('secret'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)
        ]);
        $user = User::findOrFail($id);
        $user->roles()->attach(1);
    }
}
