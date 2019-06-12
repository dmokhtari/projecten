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
            //disable foreign key check for this connection before running seeders
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach($this->toTruncate as $table) {
                DB::table($table)->truncate();
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        }

        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
