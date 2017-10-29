<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensePlatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('license_plate_styles', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('image')->unique();
            $table->timestamps();
            // TODO: protect by user role
        });

        Schema::create('license_plates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id'); // users can have unlimited plates
            $table->unsignedInteger('style_id');
            $table->string('tag', 8)->unqiue();
            $table->string('make');
            $table->string('model');
            $table->string('class');
            $table->string('color');
            $table->unsignedInteger('year');
            $table->string('image')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('style_id')->references('id')->on('license_plate_styles')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('license_plates');
        Schema::dropIfExists('license_plate_styles');
    }
}
