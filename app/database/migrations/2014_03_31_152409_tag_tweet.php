<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TagTweet extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('tag_tweet', function ($table) {
			$table->dropForeign('tag_tweet_post_id_foreign');
            $table->dropColumn('post_id');
            $table->bigInteger('tweet_id')->unsigned();
            $table->foreign('tweet_id')->references('id')->on('tweets')->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('tag_tweet', function ($table) {
            $table->dropForeign('tag_tweet_tweet_id_foreign');
            $table->dropColumn('tweet_id');
            $table->integer('post_id')->unsigned();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
	}

}
