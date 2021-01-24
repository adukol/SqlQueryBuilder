<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableManifest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest', function (Blueprint $table) {
            $table->bigIncrements("manifest_id");
            $table->unsignedBigInteger("profile_id");
            $table->foreign("profile_id")->references("profile_id")->on("profile");
            $table->string("manifest_airlineNumber");
            $table->string("manifest_airlineCode");
            $table->string("manifest_flightNo");
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
        Schema::dropIfExists('table_manifest');
    }
}
