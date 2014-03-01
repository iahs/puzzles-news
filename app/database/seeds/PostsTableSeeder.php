<?php

class PostsTableSeeder extends Seeder
{

    public function run()
    {
        foreach (
            array(
              array('title' => 'Berkman Center','website' => 'http://cyber.law.harvard.edu/','permalink' => 'http://cyber.law.harvard.edu/news/feed'),
              array('title' => 'Berkman Center','website' => 'http://cyber.law.harvard.edu/','permalink' => 'http://cyber.law.harvard.edu/newsroom/pressreleases/feed'),
              array('title' => 'Berkman Center','website' => 'http://cyber.law.harvard.edu/','permalink' => 'http://blogs.law.harvard.edu/mediaberkman/feed/'),
              array('title' => 'Bits and Pieces','website' => 'http://harry-lewis.blogspot.com/','permalink' => 'http://harry-lewis.blogspot.com/feeds/posts/default?alt=rss'),
              array('title' => 'Blown to Bits','website' => 'http://www.bitsbook.com/blog/','permalink' => 'http://www.bitsbook.com/feed/'),
              array('title' => 'Crimson Crusader','website' => 'http://thecrimsoncrusader.wordpress.com/','permalink' => 'http://thecrimsoncrusader.wordpress.com/feed/'),
              array('title' => 'Graduate School of Arts and Sciences','website' => 'http://www.gsas.harvard.edu/','permalink' => 'http://www.gsas.harvard.edu/current_students/cross_registration_information/rss.php'),
              array('title' => 'Greg Mankiw\'s Blog','website' => 'http://gregmankiw.blogspot.com/','permalink' => 'http://gregmankiw.blogspot.com/feeds/posts/default?alt=rss'),
              array('title' => 'Harvard Advocate','website' => 'http://www.theharvardadvocate.com/','permalink' => 'http://www.theharvardadvocate.com/rss.xml'),
              array('title' => 'Harvard Business Review','website' => 'http://hbr.org/','permalink' => 'http://feeds.harvardbusiness.org/harvardbusiness?format=xml'),
              array('title' => 'Harvard College Democrats','website' => 'http://www.harvarddems.com/','permalink' => 'http://www.harvarddems.com/feed/'),
              array('title' => 'Harvard Extension School News','website' => 'http://www.extension.harvard.edu/','permalink' => 'http://feeds.feedburner.com/harvardextension'),
              array('title' => 'Harvard Gazette Online','website' => 'http://news.harvard.edu/gazette/','permalink' => 'http://news.harvard.edu/gazette/feed/'),
              array('title' => 'Harvard Kennedy School','website' => 'http://www.hks.harvard.edu/','permalink' => 'http://www.hks.harvard.edu/rss/feed/news'),
              array('title' => 'Harvard Magazine','website' => 'http://harvardmagazine.com/','permalink' => 'http://feeds.feedburner.com/harvardmagazine/main'),
              array('title' => 'HBS Working Knowledge','website' => 'http://hbswk.hbs.edu/','permalink' => 'http://hbswk.hbs.edu/rss/rss.xml'),
              array('title' => 'HLS News','website' => 'http://www.law.harvard.edu/','permalink' => 'http://www.law.harvard.edu/news/rss.xml'),
              array('title' => 'HPH NOW','website' => 'http://www.hsph.harvard.edu/','permalink' => 'http://www.hsph.harvard.edu/now/rss/hph-now.xml'),
              array('title' => 'MISinformation','website' => 'http://mis-misinformation.blogspot.com/','permalink' => 'http://mis-misinformation.blogspot.com/feeds/posts/default?alt=rss'),
              array('title' => 'My Biased Coin','website' => 'http://mybiasedcoin.blogspot.com/','permalink' => 'http://mybiasedcoin.blogspot.com/feeds/posts/default?alt=rss'),
              array('title' => 'Noice','website' => 'http://verynoice.com/','permalink' => 'http://verynoice.com/feed/'),
              array('title' => 'On Harvard Time Blog','website' => 'http://onharvardtime.blogspot.com/','permalink' => 'http://feeds.feedburner.com/onharvardtimeblog'),
              array('title' => 'Radcliffe Institute','website' => 'http://www.radcliffe.harvard.edu/','permalink' => 'http://feeds.feedburner.com/radcliffe-institute'),
              array('title' => 'SEAS Newsfeed','website' => 'http://www.seas.harvard.edu/','permalink' => 'http://www.seas.harvard.edu/news-events/news-events/home-page/1/2/newsfeed/RSS'),
              array('title' => 'The Occasional Pamphlet','website' => 'http://blogs.law.harvard.edu/pamphlet/','permalink' => 'http://blogs.law.harvard.edu/pamphlet/feed/'),
              array('title' => 'The Spark','website' => 'http://www.extension.harvard.edu/','permalink' => 'http://harvardextension.wordpress.com/feed/'),
              array('title' => 'The Triple Helix','website' => 'http://thetriplehelix.org/','permalink' => 'http://thetriplehelix.org/feeds/features')
            ) as $item)
        {
            RssFeed::create($item);
        }
    }

}
