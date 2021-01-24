<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $nationality = array("Filipino", "Chinese", "Korean", "Japanese", "Italian");
        $sex = array("Male","Female");

        foreach(range(1,50)as $count){

            $ran_nationality = array_rand($nationality);
            $ran_sex = array_rand($sex);
            
            DB::table('profile')->insert([
                "profile_firstName" => STR::random(10),
                "profile_middleName" => STR::random(10),
                "profile_lastName" => STR::random(10),
                "profile_address" => STR::random(30)."_".$count,
                "profile_birthDate" => "1996-10-14",
                "profile_nationality" => $nationality[$ran_nationality],
                "profile_sex" => $sex[$ran_sex]
            ]);
        }
    }
}
