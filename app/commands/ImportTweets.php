<?php

use Illuminate\Console\Command;
include(app_path().'/libraries/tweets-importer/tweets.php');
include(app_path().'/libraries/twitter-api-php/TwitterAPIExchange.php');

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
     * Update twitter list with handles
     *
     * @return void
     */
    private function updateTwitterList($handles)
    {

        $settings = array (
            'oauth_access_token' => "2370933025-2VtN7ByF4wK5cZqDRfiiVG41XcfqoDS4vW0wAnA",
            'oauth_access_token_secret' => "MJVKlV8vI4hGV9SRB3uFRHLaLghAFSPByJvF6r8LfsD91",
            'consumer_key' => "aPoDb60HjoSbhA6A0UaOnw",
            'consumer_secret' => "wVCqksbciem5aauqF1dVtk7jp26rONcTbNPKO3v0"
        );

        $get_list_url = "https://api.twitter.com/1.1/lists/list.json";
        $requestMethod = "GET";
        $getfield = '?screen_name=PuzzlesNews';

        $twitter = new TwitterAPIExchange($settings);
        $list_response = json_decode($twitter->setGetfield($getfield)->buildOauth($get_list_url, $requestMethod)->performRequest(), $assoc = TRUE);

        // get the list id to update
        $list_1 = array_pop($list_response);
        $list_id = $list_1["id_str"];

        $url_post = "https://api.twitter.com/1.1/lists/members/create_all.json";
        $requestMethodPost = "POST";
        $postfield = "";

        while (!empty($handles)) {

            $postfield = "";

            // get the first 100 names
            for ($i = 0; $i < 100; ++$i) {
                $name = array_pop($handles);
                if ($i == 0) {
                    $postfield = $name;
                }
                else {
                    $postfield = $postfield.",".$name;
                }
                
            }

            $postfields = array("screen_name" => $postfield, "list_id" => $list_id);

            $twitter_post = new TwitterAPIExchange($settings);
            $list_response1 = json_decode($twitter_post->setPostfields($postfields)->buildOauth($url_post, $requestMethodPost)->performRequest(), $assoc = TRUE);
        }
        


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {

        // grab each of the tweeter handles
        $handles = [];
        
        foreach (Tweeter::all() as $tweeter) {

            if ($tweeter->handle) {
                // update the list with these tweeters
                array_push($handles, $tweeter->handle);

                // start importing
                $this->info('Importing tweets from ' . $tweeter->handle);
                $this->importTweet($tweeter);
            }
            
        }

        $this->info('Updating Twitter List ');
        // $this->updateTwitterList($handles);

        $this->info('Done importing tweets and updating list');
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
                    'text'		=> 'required',
                )
            );

            if ($validator->fails()) {
                return;
            }

            echo "got here1";

            $t = new Tweet(array(
                'id'	=> $tweet->get_id(),
                'text' 	=> $text,
                'tweet_time' => $tweet->get_time(),
            ));

            $t->tweeter()->associate($tweeter);
            $t->save();

            $tweeterCounter++;

            if ($tweet->get_tags()) {
                foreach($tweet->tags as $tag) {

                    print $tag->text;
                    $tag = Tag::firstOrCreate(array('name' => $tag->text));
                    $tag->tweets()->attach($tweet->get_id());
                    $tag->save();

                }
            }
            
        }

        echo "Imported $tweeterCounter tweets\n";
    }

}
