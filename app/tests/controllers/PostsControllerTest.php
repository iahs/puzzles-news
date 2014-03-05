<?php

class PostsControllerTest extends TestCase {

	/* * * * * *
	 * First, test all the different routes
	 * * * * * */
	public function testIndex()
	{
		// correct route for posts
		$this->call('GET', '/#/posts');
	}

	public function testSearch()
	{
		// correct route for search
		$this->call('GET', '/#/posts/search');
	}

	public function testInfinite()
	{
		// correct route for infinite load
		$this->call('GET', '/#/posts/infinite');
	}

	public function testTags()
	{
		// correct route for tags
		$this->call('GET', '/#/tags');
	}

	public function testClicks()
	{
		// correct route for clicks
		$this->call('GET', '/#/posts/click');
	}
}