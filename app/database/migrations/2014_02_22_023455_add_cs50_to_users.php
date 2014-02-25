<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
/**
 * Add the fields returned from the cs50 id
 * authentication service to the users table
 * Email and password for non-cs50 auth
 *
 * @return void
 */
class AddCs50ToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table) {
            $table->string('email');
            $table->string('password');
            $table->smallInteger('role')->default(1);
			$table->string('cs50id', 84);
            $table->string('cs50email');
            $table->string('cs50fullname');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('password');
            $table->dropColumn('role');
            $table->dropColumn('cs50id');
            $table->dropColumn('cs50email');
            $table->dropColumn('cs50fullname');
		});
	}

}
