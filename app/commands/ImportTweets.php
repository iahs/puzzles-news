<?php

use Illuminate\Console\Command;
include(app_path().'/libraries/tweets-importer/tweets.php');

class ImportTweets extends Command
{
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
        foreach (Tweeter::all() as $tweeter) {
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
        if (!$tweets) return;

        $tweeterCounter = 0;

        foreach ($tweets as $tweet) {

            $text = preg_replace('/[^\x20-\x7E]/', '', $tweet->get_text());

            // Enforce uniqueness and make sure the feed is valid
            $validator = Validator::make(
                array(
                    'id'		=> $tweet->get_id(),
                    'text'		=> $text,
                ),
                array(
                    'id'		=> 'required|unique:tweets',
                    'text'		=> 'required|unique:tweets',
                )
            );

            if ($validator->fails()) {
                return;
            }

            $tweet = new Tweet(array(
                'id'	=> $tweet->get_id(),
                'text' 	=> $text,
                'tweet_time' => $tweet->get_time(),
            ));

            $tweet->tweeter()->associate($tweeter);
            $tweet->save();

            $tweeterCounter++;
        }

        echo "Imported $tweeterCounter tweets\n";
    }

}
