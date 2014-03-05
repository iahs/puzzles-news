<?php

class Tweet extends Eloquent {

    # Available for mass assignment
    protected $fillable = array('id', 'text', 'time_tweeted');

    public static function validate($input) {

        $rules = array(
            'id' => 'Required',
            'text' => 'Required',
            'time_tweeted' => 'Required',
        );

        return Validator::make($input, $rules);

    }

    public function tweeter()
    {
        return $this->belongsTo('Tweeter');
    }

}

