<?php

class Post extends Eloquent {

    # Available for mass assignment
    protected $fillable = array('title', 'body');

}