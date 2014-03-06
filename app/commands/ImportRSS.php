<?php

use Illuminate\Console\Command;
include(app_path().'/libraries/alchemyapi.php');

class ImportRSS extends Command
{
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
        foreach (RssFeed::all() as $feed) {
            $this->info('Importing items from ' . $feed->title);
            $this->importFeed($feed);
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
    private function importFeed($parent)
    {
        $alchemyapi = new AlchemyAPI();

        $feed = new SimplePie();
        $feed->set_feed_url($parent->permalink);
        $feed->enable_cache(false);
        $feed->init();

        $postCounter = 0;

        foreach ($feed->get_items() as $item) {
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
            $body = strip_tags($item->get_content());

            // Presumably, the feed is invalid or we've hit old posts. Either way, we're
            //   done with this feed
            if ($validator->fails())
                return;
            $post = new Post(array(
                'title' 	  => $item->get_title(),
                'permalink'   => $item->get_permalink(),
                'body'		  => strip_tags($item->get_content()),
                'time_posted' => $item->get_gmdate('Y-m-d H:i:s'),
            ));
            $post->rssFeed()->associate($parent);
            $post->save();

            $postCounter++;

            $response = $alchemyapi->concepts('text',$body, null);
            if ($response['status'] == 'OK') {
                foreach ($response['concepts'] as $concept) {
                    echo 'concept: ', $concept['text'], PHP_EOL;
                    echo 'relevance: ', $concept['relevance'], PHP_EOL;
                    $tag = Tag::firstOrCreate(array('name' => $concept['text']));
                    $tag->posts()->attach($post->id,array('relevance'=>$concept['relevance']));
                }
            } else {
                echo 'Error in the concept tagging call: ', $response['statusInfo'];
            }
            //TODO: add other useful fields, like source, content, and date.
        }
        echo "Imported $postCounter posts\n";
    }

}
