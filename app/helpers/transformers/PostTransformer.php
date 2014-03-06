<?php namespace Helpers\Transformers;

class PostTransformer extends Transformer {
    protected $tagTransformer;

    function __construct(TagTransformer $tagTransformer)
    {
        $this->tagTransformer = $tagTransformer;
    }

    public function transform($post)
    {
        return [
            'id'             => $post['id'],
            'permalink'      => $post['permalink'],
            'time_posted'    => $post['time_posted'],
            'title'          => $post['title'],
            'body'           => $post['body'],
            'clicks'         => $post['clicks'],
            'tags'           => $this->tagTransformer->transformCollection($post['tags'])
        ];
    }
}
