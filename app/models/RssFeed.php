<?php

class RssFeed extends Eloquent {
    # Available for mass assignment
    protected $fillable = array('title', 'website', 'permalink');

    public $timestamps = false;

    public function post()
    {
        $this->hasMany('Post');
        $this->belongsTo('Tag');
    }

    public static function validate($input)
    {
        $rules = array(
            'title' => 'Required',
            'permalink' => 'Required',
            'website' => 'Required'
        );

        return Validator::make($input, $rules);
    }
}
