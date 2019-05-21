<?php
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: cristina
 * Date: 21/05/2019
 * Time: 13:08
 */
class UsersTableSeeder extends \Illuminate\Database\Seeder
{
   public function run(){

       DB::table('users')->truncate();

       factory(\App\User::class)->create([
           "username" => 'manager',
           "password" => \Illuminate\Support\Facades\Hash::make('secret')
       ]);
   }
}