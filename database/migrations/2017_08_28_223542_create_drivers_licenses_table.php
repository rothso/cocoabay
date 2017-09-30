<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->char('shortcode', 3)->unique();
        });

        Schema::create('hair_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->char('shortcode', 3)->unique();
        });

        Schema::create('drivers_licenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->unique(); // users can only have one driver's license
            $table->char('number', 9)->unique();
            $table->date('dob');
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->tinyInteger('height_in')->unsigned();
            $table->smallInteger('weight_lb')->unsigned();
            $table->integer('eye_color_id')->unsigned();
            $table->integer('hair_color_id')->unsigned();
            $table->string('address');
            $table->string('sim');
            $table->string('photo')->unique();
            $table->string('image')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('eye_color_id')->references('id')->on('eye_colors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('hair_color_id')->references('id')->on('hair_colors')->onUpdate('cascade')->onDelete('cascade');
        });

        // Seed the initial colors. To add new colors, do so in phpMyAdmin as migration files shouldn't be updated.
        DB::transaction(function() {
            DB::table('eye_colors')->insert([
                ['name' => 'Brown', 'shortcode' => 'BRN'],
                ['name' => 'Blue', 'shortcode' => 'BLU'],
                ['name' => 'Green', 'shortcode' => 'GRN'],
                ['name' => 'Hazel', 'shortcode' => 'HZL'],
                ['name' => 'Multi', 'shortcode' => 'MLT'],
                ['name' => 'Pink', 'shortcode' => 'PNK'],
                ['name' => 'Red', 'shortcode' => 'RED'],
                ['name' => 'Violet', 'shortcode' => 'VIO'],
            ]);
        });

        DB::transaction(function () {
            DB::table('hair_colors')->insert([
                ['name' => 'Brown', 'shortcode' => 'BRN'],
                ['name' => 'Black', 'shortcode' => 'BLK'],
                ['name' => 'Blond', 'shortcode' => 'BLO'],
                ['name' => 'Multi', 'shortcode' => 'MLT'],
                ['name' => 'Red', 'shortcode' => 'RED'],
                ['name' => 'Violet', 'shortcode' => 'VIO'],
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
