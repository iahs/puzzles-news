<?php

class Tweet extends Eloquent {

    # Available for mass assignment
    protected $fillable = array('text');

    public static function validate($input) {

        $rules = array(
            'text' => 'Required',
        );

        return Validator::make($input, $rules);

    }

    public function tweeter()
    {
        return $this->belongsTo('Tweeter');
    }

}

