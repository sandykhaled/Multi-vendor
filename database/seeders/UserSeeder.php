<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'sandy',
            'email'=>'sandykhaled1203@gmail.com',
            'password'=>Hash::make('1234566'),
            'phone_number'=>'010066353003'
        ]);
//        DB::table('users')->insert([
//            'name'=>'sara',
//            'email'=>'sara@sara.com',
//            'password'=>Hash::make('12345678'),
//            'phone_number'=>'154365868633'
//        ]);
    }
}
