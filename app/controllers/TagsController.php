<?php

use Helpers\Transformers\TagTransformer;

class TagsController extends BaseApiController {

    protected $tagTransformer;

    function __construct(TagTransformer $tagTransformer)
    {
        $this->tagTransformer = $tagTransformer;
    }

    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $tags = Tag::all();
        return Response::json([
            'data' => $this->tagTransformer->transformCollection($tags->toArray())
        ], 200);
	}
}
