<?php

	include_once('Internal_Tweet.php');

	/**
	 * Twitter feed which uses twitteroauth for authentication
	 * 
	 * @version	1.0
	 * @author	Andrew Biggart
	 * @link	https://github.com/andrewbiggart/latest-tweets-php-o-auth/
	 * 
	 * Notes:
	 * Caching is employed because Twitter only allows their RSS and json feeds to be accesssed 150
	 * times an hour per user client.
	 * --
	 * Dates can be displayed in Twitter style (e.g. "1 hour ago") by setting the 
	 * $twitter_style_dates param to true.
	 *
	 * You will also need to register your application with Twitter, to get your keys and tokens.
	 * You can do this here: (https://dev.twitter.com/).
	 *
	 * Don't forget to add your username to the bottom of the script.
	 * 
	 * Credits:
	 ***************************************************************************************
	 * Initial script before API v1.0 was retired
	 * http://f6design.com/journal/2010/10/07/display-recent-twitter-tweets-using-php/
	 *
	 * Which includes the following credits
	 * Hashtag/username parsing based on: http://snipplr.com/view/16221/get-twitter-tweets/
	 * Feed caching: http://www.addedbytes.com/articles/caching-output-in-php/
	 * Feed parsing: http://boagworld.com/forum/comments.php?DiscussionID=4639
	 ***************************************************************************************
	 *
	 ***************************************************************************************
	 * Authenticating a User Timeline for Twitter OAuth API V1.1
	 * http://www.webdevdoor.com/php/authenticating-twitter-feed-timeline-oauth/
	 ***************************************************************************************
	 *
	 ***************************************************************************************
	 * Twitteroauth which has been used for the authentication process
	 * https://github.com/abraham/twitteroauth
	 ***************************************************************************************
	 *
	 *
	**/
 
	// Require Config files
	require_once("config.php");

	// Start output buffering.
	// ob_start();
	
	// Set timezone. (Modify to match your timezone) If you need help with this, you can find it here. (http://php.net/manual/en/timezones.php)
	date_default_timezone_set('America/New_York');
	
	// Require TwitterOAuth files. (Downloadable from : https://github.com/abraham/twitteroauth)
	require_once("twitteroauth/twitteroauth/twitteroauth.php");
	
	// Function to authenticate app with Twitter.
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	  return $connection;
	}
	
	// Function to display the latest tweets.
	function display_latest_tweets(

		// Function parameters.
		$twitter_user_id,
		$cache_file          = './tweets.txt',  // Change this to the path of your cache file. (Default : ./tweets.txt)
		$tweets_to_display   = 50,               // Number of tweets you would like to display. (Default : 5)
		$ignore_replies      = false,           // Ignore replies from the timeline. (Default : false)
		$include_rts         = false,           // Include retweets. (Default : false)
		$twitter_wrap_open   = '<ul class="home-tweets-ul">',
		$twitter_wrap_close  = '</ul>',
		$tweet_wrap_open     = '<li><p class="home-tweet-tweet">',
		$meta_wrap_open      = '<br/><span class="home-tweet-date">',
		$meta_wrap_close     = '</span>',
		$tweet_wrap_close    = '</p></li>',
		$date_format         = 'g:i A M jS',    // Date formatting. (http://php.net/manual/en/function.date.php)
		$twitter_style_dates = true){           // Twitter style days. [about an hour ago] (Default : true)
		
		// Seconds to cache feed 
		$cachetime           = 60 * 3;
		
		// Time that the cache was last updtaed.
		$cache_file_created  = ((file_exists($cache_file))) ? filemtime($cache_file) : 0;
 
		// A flag so we know if the feed was successfully parsed.
		$tweet_found         = false;

		$consumerkey         = CONSUMER_KEY;
		$consumersecret      = CONSUMER_SECRET;
		$accesstoken         = ACCESS_TOKEN;
		$accesstokensecret   = ACCESS_SECRET;
		
		// Cache file not found, or old. Authenticae app.
		$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
		
			if($connection){
				// Get the latest tweets from Twitter
 				$get_tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitter_user_id."&count=".$tweets_to_display."&include_rts=".$include_rts."&exclude_replies=".$ignore_replies);
			

				// Error check: Make sure there is at least one item.
				if (count($get_tweets)) {
 					
 					// Create Tweets array
 					$tweets_array = [];

					// Define tweet_count as zero
					$tweet_count = 0;
 
					// Open the twitter wrapping element.
					// $twitter_html = $twitter_wrap_open;
					$twitter_html = "";
 
					// Iterate over tweets.
					foreach($get_tweets as $tweet) {

						if (!is_object($tweet)) {
							break;
						}

						$tweet_found = true;
						$tweet_count++;
						$tweet_desc = $tweet->text;
						$tweet_entities = $tweet->entities;
						$tweet_tags = [];
						if ($tweet_entities) {
							$tweet_tags = $tweet_entities->hashtags;
						}

						// Convert Tweet display time to a UNIX timestamp. Twitter timestamps are in UTC/GMT time.
						$tweet_time = strtotime($tweet->created_at);	
						if ($twitter_style_dates){
						// Current UNIX timestamp.
						$current_time = time();
						$time_diff = abs($current_time - $tweet_time);
						switch ($time_diff) 
						{
							case ($time_diff < 60):
								$display_time = $time_diff.' seconds ago';                  
								break;      
							case ($time_diff >= 60 && $time_diff < 3600):
								$min = floor($time_diff/60);
								$display_time = $min.' minutes ago';                  
								break;      
							case ($time_diff >= 3600 && $time_diff < 86400):
								$hour = floor($time_diff/3600);
								$display_time = 'about '.$hour.' hour';
								if ($hour > 1){ $display_time .= 's'; }
								$display_time .= ' ago';
								break;          
							default:
								$display_time = date($date_format,$tweet_time);
								break;
						}
						} else {
							$display_time = date($date_format,$tweet_time);
						}
 
						// Render the tweet.
						$twitter_html .= $twitter_user_id.",".html_entity_decode($tweet_desc).",".$display_time."\n";

						// Store as an object
						$tweet = new Internal_Tweet($tweet->id, $tweet_desc, $tweet_time, $tweet_tags);
						array_push($tweets_array, $tweet);
 
					}
 
					// Close the twitter wrapping element.
					// $twitter_html .= $twitter_wrap_close;
					// echo ">>>>>".$twitter_html;
 
					// Generate a new cache file.
					// $file = fopen($cache_file, 'w');
 
					// Save the contents of output buffer to the file, and flush the buffer. 
					// fwrite($file, ob_get_contents()); 
					// fclose($file); 
					// ob_end_flush();

					return $tweets_array;
					
				}
				
			}
			
		// }
		
	}
	
	// Display latest tweets. (Modify username to your Twitter handle)
	// display_latest_tweets('taylorswift13');
?>