<?php

class Post extends Eloquent {

    # Available for mass assignment
    protected $fillable = array('title', 'body', 'permalink');

    # Load tags
    protected $with = array('tags');

    public static function validate($input) {

        $rules = array(
            'title' => 'Required',
            'body' => 'Required'
        );

        return Validator::make($input, $rules);

    }

    public function rssFeed()
    {
        return $this->belongsTo('RssFeed');
    }

    public function tags()
    {
        return $this->belongsToMany('Tag','tag_post');
    }
}

