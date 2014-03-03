<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Trim extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('authors');
		Schema::dropIfExists('category_post');
		Schema::dropIfExists('category_tweet');
		Schema::dropIfExists('categories');
		Schema::dropIfExists('media');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		echo "Rerun the migrations if you really want to... \n";
	}

}
