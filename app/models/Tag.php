<?php

class Tag extends Eloquent {
	protected $guarded = array();

	public static $rules = array();

    public function post()
    {
        return $this->belongsToMany('Post','tag_post');
    }
}
