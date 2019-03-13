<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * @var array can be truncated tables
     */
    protected $toTruncate = ['users', 'roles'];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') === 'local') {
            foreach($this->toTruncate as $table) {
                DB::truncate($table);
            }
        }
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
