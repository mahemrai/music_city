<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('discogsId', 250);
            $table->string('name', 100);
            $table->mediumText('bio');
            $table->string('website', 250);
            $table->string('wiki', 250)->nullable();
            $table->string('image', 500);
            $table->string('thumb', 500);
            $table->string('members', 500);
            $table->boolean('favourite')->nullable();
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
        Schema::drop('artists');
    }
}
