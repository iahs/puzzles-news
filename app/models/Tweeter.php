<?php

class Tweeter extends Eloquent {

    /**
     * Twitter handles
     *
     */

	protected $guarded = array();

	public static $rules = array();

	public $timestamps = false;

	public function post()
    {
        $this->hasMany('Tweets');
    }
}
