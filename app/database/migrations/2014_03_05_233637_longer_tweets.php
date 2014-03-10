<?php

use Illuminate\Database\Migrations\Migration;

class LongerTweets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tweets', function ($table) {
            $table->dropColumn('text');
        });
        Schema::table('tweets', function ($table) {
            $table->string('text',255)->after('tweeter_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tweets', function ($table) {
            $table->dropColumn('text');
        });
        Schema::table('tweets', function ($table) {
            $table->string('text',140)->after('tweeter_id');
        });
    }

}
