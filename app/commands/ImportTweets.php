<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
include(app_path().'/libraries/tweets-importer/tweets.php');

class ImportTweets extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:importtweets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import Tweets to database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		foreach (Tweeter::all() as $tweeter)
		{
			$this->info('Importing tweets from ' . $tweeter->handle);
			$this->importTweet($tweeter);
		}
		$this->info('Done importing tweets');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}

	/**
	 * Import entries from tweet to database
	 * @return none
	 */
	private function importTweet($tweeter)
	{

		$tweets = display_latest_tweets($tweeter->handle);

		$tweeterCounter = 0;

		foreach ($tweets as $tweet)
		{
			print($tweet->get_text()."\n");

			// Enforce uniqueness and make sure the feed is valid
			$validator = Validator::make(
			    array(
			    	'id'		=>$tweet->get_id(),
			    	'text'		=> $tweet->get_text(),
			    ),
			    array(
			    	'id'		=> 'required|unique:tweets',
			    	'text'		=> 'required|unique:tweets',
			    )
			);

			// Presumably, the feed is invalid or we've hit old posts. Either way, we're
			//   done with this feed
			if ($validator->fails()) {
				print(">>>>> FAIL\n");
				return;
			}

			$tweet = new Tweet(array(
				'id'	=>$tweet->get_id(),
	            'text' 	=> $tweet->get_text(),
	        ));

	        $tweet->tweeter()->associate($tweeter);
	        $tweet->save();

	        $tweeterCounter++;
		}

		echo "Imported $tweeterCounter tweets\n";
	}

}
