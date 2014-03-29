<?php

use Helpers\Transformers\PostTransformer;

class PostsController extends BaseApiController
{
    /**
     * @var Helpers\Transformers\PostTransformer;
     */
    protected $postTransformer;

    /**
     * @var Post
     */
    protected $post;

    /**
     * @param PostTransformer $postTransformer
     * @param Post $post
     */
    public function __construct(PostTransformer $postTransformer, Post $post)
    {
        $this->postTransformer = $postTransformer;
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $posts = $this->post->all();

        return Response::json([
            'data' => $posts->toArray()
        ], 200);
    }

    /**
     * Store a oldly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (! $this->post->isValid($input = Input::json()->all()))
        {
            return Response::json([
                'errors' => $this->post->errors->toArray(),
                'message' => 'Validation failed'
            ], 400);
        }

        // validation passed
        $post = $this->post->create($input);
        return Response::json([
            'data' => $post->toArray()
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        Post::destroy($id);

        return Response::json([
            'success' => true
        ], 200);
    }

    /**
     * Take a fulltext query string and a list of tag ids from the user
     * and return a list of corresponding posts
     * Display a listing of post with id<requested
     * Gets all posts if no query or tags
     * @return \Illuminate\Http\JsonResponse
     */
    public function infinite()
    {
        $query = Input::get('query', "");
        $oldest = Input::get('oldest', 0) > 0 ? Input::get('oldest') : time();
        $oldest = date('Y-m-d H:i:s',$oldest);
        $limit = Input::get('limit', 20);

        // Convert comma separated string to array of integer tag ids
        $tagStrings = array_filter(explode(',', Input::get('tags', '')));
        $tagIds = array_map("intval", $tagStrings);

        $postIds = $this->post->getIdsWithQueryTagsAndRelevance($query, $tagIds);

        if (empty($postIds)) {
            // No posts remaining
            return Response::json([
                'data' => [],
                'message' => 'No more posts remaining'
            ], 200);
        }

        $posts = $this->post->getForInfiniteByTime($postIds, $oldest, $limit);
        return Response::json([
            'data' => $this->postTransformer->transformCollection($posts->toArray())
        ], 200);

    }

    public function click()
    {
        $id = Input::get('id');
        if (!$id) return Response::Make('',400);
        $click = Click::firstOrCreate(array('post_id'=>$id,'date'=>date('Y-m-d')));
        $click->increment('clicks');

        return Response::Make('',200);
    }

}
