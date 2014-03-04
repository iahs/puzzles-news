<?php

class Click extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('Post');
    }
}
