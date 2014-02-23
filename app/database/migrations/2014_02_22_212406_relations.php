<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Relations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('tweets');
		Schema::create('tweets', function($table)
		{
		    $table->bigInteger('id')->unsigned();
		    $table->primary('id');
		    $table->integer('tweeter_id')->unsigned();
		    $table->string('text',140);
		    $table->timestamps();
		    $table->foreign('tweeter_id')->references('id')->on('tweeters');
		});
		Schema::dropIfExists('tags');
		Schema::create('tags', function($table)
		{
		    $table->increments('id');
		    $table->string('name');
		    $table->string('description');
		    $table->timestamps();
		});
		Schema::dropIfExists('categories');
		Schema::create('categories', function($table)
		{
		    $table->increments('id');
		    $table->string('name');
		    $table->string('description');
		    $table->timestamps();
		});
		Schema::create('tag_post', function($table)
		{
		    $table->increments('id');
		    $table->integer('tag_id')->unsigned();
		    $table->integer('post_id')->unsigned();
		    $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
		    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
		});
		Schema::create('category_post', function($table)
		{
		    $table->increments('id');
		    $table->integer('category_id')->unsigned();
		    $table->bigInteger('tweet_id')->unsigned();
		    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		    $table->foreign('tweet_id')->references('id')->on('tweets')->onDelete('cascade');
		});
		Schema::create('tag_tweet', function($table)
		{
		    $table->increments('id');
		    $table->integer('tag_id')->unsigned();
		    $table->integer('post_id')->unsigned();
		    $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
		    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
		});
		Schema::create('category_tweet', function($table)
		{
		    $table->increments('id');
		    $table->integer('category_id')->unsigned();
		    $table->bigInteger('tweet_id')->unsigned();
		    $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		    $table->foreign('tweet_id')->references('id')->on('tweets')->onDelete('cascade');
		});
		Schema::create('clicks', function($table)
		{
		    $table->increments('id');
		    $table->integer('post_id')->unsigned();
		    $table->date('date');
		    $table->integer('clicks')->unsigned();
		    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
		});
		Schema::table('posts', function($table)
		{
		    $table->unique('permalink');
		    $table->dropColumn('feed_id');
		    $table->dropColumn('author_id');
		    $table->integer('rss_feed_id')->unsigned();
		    $table->foreign('rss_feed_id')->references('id')->on('rss_feeds');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('tweets');
		Schema::dropIfExists('categories');
		Schema::dropIfExists('tag_post');
		Schema::dropIfExists('category_post');
		Schema::dropIfExists('tag_tweet');
		Schema::dropIfExists('category_tweet');
		Schema::dropIfExists('clicks');
	}

}
