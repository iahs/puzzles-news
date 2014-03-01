<?php

class RssFeed extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

    public $timestamps = false;

    public function post()
    {
        $this->hasMany('Post');
    }
}
