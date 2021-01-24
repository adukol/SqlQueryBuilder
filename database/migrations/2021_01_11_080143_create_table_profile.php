<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile', function (Blueprint $table) {
            $table->bigIncrements("profile_id");
            $table->string("profile_firstName");
            $table->string("profile_middleName");
            $table->string("profile_lastName");
            $table->string("profile_address");
            $table->date("profile_birthDate");
            $table->string("profile_sex");
            $table->string("profile_nationality");
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
        Schema::dropIfExists('table_profile');
    }
}
