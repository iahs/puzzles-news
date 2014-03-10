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
            'title' => $post['title'],
            'body' => $post['body'],
            'tags' => $this->tagTransformer->transformCollection($post['tags']),
            'created_at' => $post['created_at']
        ];
    }
} 