<?php namespace Helpers\Transformers;

class TweetTransformer extends Transformer {
    protected $tagTransformer;

    function __construct(TagTransformer $tagTransformer)
    {
        $this->tagTransformer = $tagTransformer;
    }

    public function transform($tweet)
    {
        return [
            'id'             => $tweet['id'],
            'time_tweeted'    => $tweet['time_tweeted'],
            'text'          => $tweet['text'],
            'tweeter_id'    => $tweet['tweeter_id']
        ];
    }
}
