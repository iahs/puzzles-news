<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Defaults extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('clicks', function ($table) {
            $table->dropColumn('clicks');
        });
        Schema::table('clicks', function ($table) {
            $table->integer('clicks')->unsigned()->default(0);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('clicks', function ($table) {
            $table->dropColumn('clicks');
        });
        Schema::table('clicks', function ($table) {
            $table->integer('clicks')->unsigned();
        });
	}

}
