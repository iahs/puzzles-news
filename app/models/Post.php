<?php

use Illuminate\Database\Eloquent\Collection;

class Post extends Eloquent
{
    # Available for mass assignment
    protected $fillable = array('title', 'body', 'permalink', 'time_posted');

    # Load tags
    protected $with = array('tags');

    # Validation rules
    protected $rules = array(
        'title' => 'Required',
        'body' => 'Required'
    );

    # Validation errors on the instance
    public $errors;

    /**
     * Validate a set of inputs for the model
     * @param $input
     * @return bool
     */
    public function isValid($input)
    {
        $validation = Validator::make($input, $this->rules);

        if ($validation->passes()) return true;

        $this->errors = $validation->messages();
        return false;
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

    /**
     * Return an array of post ids that matches the fulltext query string and
     * comma-separated list of tag-ids
     * @param $query
     * @param $tagIds comma-separated string of tag-ids
     * @param int $minRelevance minimum required tag relevance
     * @return array
     */
    public function getIdsWithQueryTagsAndRelevance($query, $tagIds, $minRelevance = 0) {
        $idObjects = DB::table('tag_post')->join('posts', 'posts.id', '=', 'tag_post.post_id');

        if (! empty($query))
            $idObjects->whereRaw("MATCH(posts.title, posts.body) AGAINST(?)", array($query));
        if (! empty($tagIds)) {
            $idObjects->whereIn('tag_post.tag_id', $tagIds, 'and');
            $idObjects->where('relevance', '>', $minRelevance);
        }

        // Get the ids of all posts that match the query and tag ids
        $idObjects = $idObjects->select('posts.id as postid')->distinct()->get();

        // Convert array of stdObj to an integer array
        $postIds = [];
        foreach ($idObjects as $post) {
            array_push($postIds, $post->postid);
        }
        return $postIds;
    }

    public function getForInfiniteByTime($postIds, $olderThan, $limit=20) {
        return Post::whereIn('id', $postIds, 'or')
            ->orderBy('time_posted', 'desc')
            ->where('time_posted', '<', $olderThan)
            ->take($limit)
            ->get();
    }

    public function getPopular($clickedAfter=0, $limit=20) {
        $date = date('Y-m-d', $clickedAfter);
        $clicks = Click::select('post_id')->orderBy(DB::raw("SUM(IF(date>=$date,clicks,0))"))
            ->groupBy('post_id')
            ->take($limit)
            ->get();

        // Convert array of stdObj to an integer array
        $postIds = [];
        foreach ($clicks as $click) {
            array_push($postIds, $click->post_id);
        }
        return $postIds;
    }
}
