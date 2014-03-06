<?php

class Post extends Eloquent
{
    # Available for mass assignment
    protected $fillable = array('title', 'body', 'permalink', 'time_posted');

    # Load tags
    protected $with = array('tags');

    public static function validate($input)
    {
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
        return $this->belongsToMany('Tag','tag_post')->withPivot('relevance');
    }

    # Get click count with post
    public function toArray()
    {
        $array = parent::toArray();
        $array['clicks'] = $this->clicks;
        return $array;
    }
    public function getClicksAttribute()
    {
        return Click::where('post_id', $this->id)->sum('clicks');
    }

    public function getTimePostedAttribute($value)
    {
        return strtotime($value);
    }
}
