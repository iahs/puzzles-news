<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MergePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('posts', function($table)
		{
          
            $table->integer('author_id');
            $table->boolean('media');
            $table->integer('feed_id');
            $table->foreign('author_id')->references('id')->on('authors');
            $table->foreign('feed_id')->references('id')->on('rss');
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
