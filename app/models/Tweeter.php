<?php

class Tweeter extends Eloquent {

    /**
     * Twitter handles
     *
     */
    protected $table = 'users';

	protected $guarded = array();

	public static $rules = array();
}
