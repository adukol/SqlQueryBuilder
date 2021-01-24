<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PassportDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = array("Philippines", "Japan", "Korea", "China", "Italy", "Russia");

        foreach(range(1,50) as $count){

            $ran_country = array_rand($country,1);
            
            DB::table('passport_detail')->insert([
                "profile_id" => $count,
                "pd_passportNumber" => STR::random(10).$count,
                "pd_country" => $country[$ran_country],
                "pd_dateIssued" => "2021-1-14",
                "pd_dateValid" => "2024-1-14",
                "pd_placeIssued" => $country[$ran_country]
            ]);
        }
    }
}
