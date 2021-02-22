<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::factory()->create([
      'name' => env('ADMIN_NAME'),
      'email' => env('ADMIN_EMAIL'),
      'password' => bcrypt(env('ADMIN_PASSWORD')),
      'is_admin' => true
    ]); 
  }
}
