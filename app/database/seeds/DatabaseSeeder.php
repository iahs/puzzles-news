<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('PostsTableSeeder');
        $this->command->info('Feeds table seeded; call command:importrss to load posts');


        $this->call('TweetersTableSeeder');
        $this->command->info('Tweeters table seeded');
	}

}
