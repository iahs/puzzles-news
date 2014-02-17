<?php

class Post extends Eloquent {

    # Available for mass assignment
    protected $fillable = array('title', 'body');

    public static function validate($input) {

        $rules = array(
            'title' => 'Required',
            'body' => 'Required'
        );

        return Validator::make($input, $rules);

    }

}