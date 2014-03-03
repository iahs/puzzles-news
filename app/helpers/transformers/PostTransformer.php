<?php namespace Helpers\Transformers;

class PostTransformer extends Transformer {
    public function transform($post)
    {
        return [
            'title' => $post['title'],
            'body' => $post['body']
        ];
    }

} 