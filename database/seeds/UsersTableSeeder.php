<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamps = Carbon::now();
        DB::table('users')->insert(array(
            array(
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456789'),
                'role' => 'Admin',
                'created_at' => $timestamps,
                'updated_at' => $timestamps,
            ),
            array(
                'name' => 'customer',
                'email' => 'customer@mail.com',
                'password' => bcrypt('123456789'),
                'role' => 'User',
                'created_at' => $timestamps,
                'updated_at' => $timestamps,
            ),
        ));
    }
}
