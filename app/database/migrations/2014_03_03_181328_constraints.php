<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Constraints extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rss_feeds', function($table)
		{
			$table->dropColumn('tag_id');
		});
		Schema::table('rss_feeds', function($table)
		{
			$table->integer('tag_id')->nullable()->unsigned();
			$table->foreign('tag_id')->references('id')->on('tags');
			$table->unique('permalink');
		});
		Schema::table('clicks', function($table)
		{
			$table->unique(array('post_id','date'));
		});
		Schema::table('posts',function($table)
		{
			$table->dropColumn('created_at');
			$table->dropColumn('updated_at');
		});
		Schema::table('posts',function($table)
		{
			$table->timestamps();
		});
		Schema::table('tag_post', function($table)
		{
			$table->decimal('relevance',6,6);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rss_feeds', function($table)
		{
			$table->dropForeign('rss_feeds_tag_id_foreign');
			$table->dropUnique('rss_feeds_permalink_unique');
		});
		Schema::table('clicks', function($table)
		{
			$table->dropUnique('clicks_post_id_date_unique');
		});
		Schema::table('tag_post', function($table)
		{
			$table->dropColumn('relevance');
		});
	}

}
