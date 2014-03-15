<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersTweeters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function ($table) {
            $table->dropColumn('username');
            $table->integer('tweeter_id')->unsigned()->nullable();
            $table->foreign('tweeter_id')->references('id')->on('tweeters');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function ($table) {
            $table->text('username');
            $table->dropForeign('users_tweeter_id_foreign');
            $table->dropColumn('tweeter_id');
        });
	}

}
