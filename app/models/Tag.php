<?php

class Tag extends Eloquent {

	protected $guarded = array();

	public static $rules = array();

    public function posts()
    {
        return $this->belongsToMany('Post','tag_post')->withPivot('relevance');
    }

    public function tweets()
    {
        return $this->belongsToMany('Tweet');
    }
}
