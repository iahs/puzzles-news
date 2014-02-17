<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportRSS extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:importrss';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import RSS feeds to database.';

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
		$lines = file('app/config/feeds.db');
		if(!$lines) return $this->error('No RSS feeds found in app/config/feeds.db');
		foreach ($lines as $line)
		{
			$this->info('Importing items from ' . $line);
			$this->importFeed($line);
		}
		$this->info('Done importing feeds');
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
	 * Import entries from feed to database
	 * @return none
	 */
	private function importFeed($url)
	{
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->enable_cache(false);
		$feed->init();

		foreach ($feed->get_items() as $item)
		{
			// Enforce uniqueness and make sure the feed is valid
			$validator = Validator::make(
			    array(
			    	'title'		=> $item->get_title(),
			        'permalink' => $item->get_permalink()
			    ),
			    array(
			    	'title'		=> 'required',
			        'permalink' => 'required|url|unique:posts'
			    )
			);
			// Presumably, the feed is invalid or we've hit old posts. Either way, we're
			//   done with this feed
			if ($validator->fails())
				return;
			$post = Post::create(array(
	            'title' 	=> $item->get_title(),
	            'permalink' => $item->get_permalink(),
	            'body'		=> strip_tags($item->get_content())
	        ));
	        //TODO: add other useful fields, like source, content, and date.
		}
	}

}
