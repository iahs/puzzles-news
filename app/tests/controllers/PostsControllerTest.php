<?php

class PostsControllerTest extends TestCase {

	/* * * * * *
	 * First, test all the different routes
	 * * * * * */
	public function testIndex()
	{
		// correct route for posts
		$this->call('GET', '/api/posts');
	}

	public function testSearch()
	{
		// correct route for search
		$this->call('GET', '/api/posts/search');
	}

	public function testInfinite()
	{
		// correct route for infinite load
		$this->call('GET', '/api/posts/infinite');
	}

	public function testTags()
	{
		// correct route for tags
		$this->call('GET', '/api/tags');
	}

	public function testClicks()
	{
		// correct route for clicks
		$this->call('POST', '/api/posts/click');
	}
}