<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDate extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//  Add time stamp of time tweeted
		Schema::table('tweets', function($table)
		{
            $table->timestamp('time_tweeted')->after('text');
		});

		// Add time_posted
		Schema::table('posts', function($table)
		{
            $table->timestamp('time_posted')->after('body');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//  Drop time_tweeted column
		Schema::table('tweets', function($table)
        {
            $table->drop('time_tweeted');
        });

		// Drop time_posted column
        Schema::table('posts', function($table)
        {
            $table->drop('time_posted');
        });
	}

}
