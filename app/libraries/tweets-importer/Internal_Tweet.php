<?php

// Abstraction for our own Internal_Tweet Data
class Internal_Tweet {

    var $text;
    var $time;
    var $tags;

    // constructor
	function Internal_Tweet($id, $text, $time = NULL, $tags) 
	{
		$this->id = $id;
		$this->text = $text;
		$this->tags = $tags;
	    if (!$time) {
	    	$this->time = time();
 	    }
 	    else {
	    	$this->time = $time;
 	    }
	}

	// getter method for id
	function get_id()
	{
		return $this->id;
	}
	
	// getter method for text
	function get_text()
	{
		return $this->text;
	}

	// getter method for text
	function get_time()
	{
		return $this->time;
	}

	// geter method for tags
	function get_tags() {
		return $this->tags;
	}
   
} // end of class Internal_Tweet
