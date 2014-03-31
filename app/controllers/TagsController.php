<?php

use Helpers\Transformers\TagTransformer;

class TagsController extends BaseApiController {

    protected $tagTransformer;

    function __construct(TagTransformer $tagTransformer, Tag $tag)
    {
        $this->tagTransformer = $tagTransformer;
        $this->tag = $tag;
    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $tags = $this->tag->getAllWithPosts();
        return Response::json([
            'data' => $this->tagTransformer->transformCollection($tags->toArray())
        ], 200);
	}
}
