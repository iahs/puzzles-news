<?php

class TweetersTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('tweeters')->delete();
        ini_set('auto_detect_line_endings', true);

        if (($handle = fopen(app_path() . "/database/seeds/tweeters.csv", "r")) !== FALSE) {
            while ($line = fgetcsv($handle, ",")) {
                Tweeter::create(array(
                    'handle' => $line[0],
                    'name' => $line[1],
                    'website' => $line[2],
                    'description' => $line[3],
                    'image' => $line[4]
                ));
            }
        }
        ini_set('auto_detect_line_endings', false);
    }

}

