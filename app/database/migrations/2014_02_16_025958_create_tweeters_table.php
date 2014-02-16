<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTweetersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tweeters', function(Blueprint $table) {
			$table->increments('id');

			$table->text('handle');
            $table->text('name');
            $table->text('website');
            $table->text('description');
            $table->text('image');

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
		Schema::drop('tweeters');
	}

}
