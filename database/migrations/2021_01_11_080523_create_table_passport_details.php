<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePassportDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passport_detail', function (Blueprint $table) {
            $table->increments("pd_id");
            $table->integer("profile_id")->unsigned()->index();
            $table->foreign("profile_id")->references('profile_id')->on('profile'); 
            $table->string("pd_passportNumber");
            $table->string("pd_country");
            $table->date("pd_dateIssued");
            $table->date("pd_dateValid");
            $table->string("pd_placeIssued");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_passport_details');
    }
}
