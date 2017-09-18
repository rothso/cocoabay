<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriversLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception|Throwable
     */
    public function up()
    {
        Schema::create('eye_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
        });

        Schema::create('hair_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
        });

        Schema::create('drivers_licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique(); // users can only have one driver's license
            $table->date('dob');
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->tinyInteger('height_in')->unsigned();
            $table->smallInteger('weight_lb')->unsigned();
            $table->integer('eye_color_id')->unsigned();
            $table->integer('hair_color_id')->unsigned();
            $table->string('address');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('eye_color_id')->references('id')->on('eye_colors')->onUpdate('cascade');
            $table->foreign('hair_color_id')->references('id')->on('hair_colors')->onUpdate('cascade');
        });

        // Seed the initial colors. To add new colors, do so in phpMyAdmin as migration files shouldn't be updated.
        DB::transaction(function() {
            DB::table('eye_colors')->insert([
                ['name' => 'Brown'],
                ['name' => 'Blue'],
                ['name' => 'Green'],
                ['name' => 'Hazel'],
                ['name' => 'Multi'],
                ['name' => 'Pink'],
                ['name' => 'Purple'],
                ['name' => 'Red'],
            ]);
        });

        DB::transaction(function () {
            DB::table('hair_colors')->insert([
                ['name' => 'Brown'],
                ['name' => 'Black'],
                ['name' => 'Blond'],
                ['name' => 'Multi'],
                ['name' => 'Purple'],
                ['name' => 'Red'],
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers_licenses');
        Schema::dropIfExists('hair_colors');
        Schema::dropIfExists('eye_colors');
    }
}
