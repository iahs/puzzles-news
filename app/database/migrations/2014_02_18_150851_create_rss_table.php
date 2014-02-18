<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('rss_feeds', function(Blueprint $table) {
            $table->integer('feed_id');
            $table->string('title');
			$table->string('website');
			$table->string('permalink');
            $table->integer('tag_id');
            $table->primary('feed_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
