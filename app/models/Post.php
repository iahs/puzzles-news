<?php

class Post extends Eloquent {

    # Available for mass assignment
    protected $fillable = array('title', 'body', 'permalink');

    public static function validate($input) {

        $rules = array(
            'title' => 'Required',
            'body' => 'Required'
        );

        return Validator::make($input, $rules);

    }

    public function rss_feed()
    {
        return $this->belongsTo('Rss_feed');
    }
    
    public function tag()
    {
        return $this->hasMany('Tag','tag_post'));
    }
}

