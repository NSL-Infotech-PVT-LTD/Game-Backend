<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
      $data = [
          'first_name' => 'admin',
          'last_name' => 'admin',
          'email' => 'admin@win_chunks.com',
          'password' => Hash::make('12345678'),
          // 'phone' => '98166422',

      ];
      $user = \App\User::create($data);
      $user->assignRole('Super-Admin');
    }

}
