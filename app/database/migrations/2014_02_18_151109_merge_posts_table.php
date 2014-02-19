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
		Schema::table('posts', function(Blueprint $table)
		{
            $table->integer('feed_id');
            $table->integer('author_id');
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
        Schema::table('posts', function($table)
        {
            $table->dropForeign('feed_id');
            $table->dropColumn('author_id');
        });
	}

}
