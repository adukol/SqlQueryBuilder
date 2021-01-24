<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManifestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for( $count = 1; $count <=50; $count++){

            DB::table('manifest')->insert([
                "profile_id" => $count,
                "manifest_airlineNumber" => STR::random(10).$count,
                "manifest_airlineCode" => STR::random(10),
                "manifest_flightNo" => STR::random(10)
            ]);
        }
    }
}
