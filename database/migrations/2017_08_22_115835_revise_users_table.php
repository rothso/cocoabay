<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReviseUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Column modifications must be separate because of a leaky abstraction involving
        // SQLite (which we use for unit testing).

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->default('');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('uuid')->unique()->default('');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->default('');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'username']);
        });
    }
}
