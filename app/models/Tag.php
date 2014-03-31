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

    public function getAllWithPosts()
    {
        $tagObjects = DB::table("tag_post")->distinct()->get();
        // Convert array of stdObj to an integer array
        $tagIds = [];
        foreach ($tagObjects as $tag) {
            array_push($tagIds, $tag->tag_id);
        }
        return Tag::whereIn('id', $tagIds)->get();
    }
}
