<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        $user = app()->make('App\User');
        $hasher = app()->make('hash');

        $user->fill(
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'password' => $hasher->make('user1234'),
                'is_admin' => 1
            ]
        ); 
        $user->save();
    }
}
