<?php

use Helpers\Transformers\PostTransformer;
use Illuminate\Support\Facades\Response;

class PostsController extends BaseApiController {

    /**
     * @var Acme\Transformers\PostTransformerPostTransformer
     */
    protected $postTransformer;

    function __construct(PostTransformer $postTransformer)
    {
        $this->postTransformer = $postTransformer;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $posts = Post::all();
        return Response::json([
            'data' => $posts->toArray()
        ], 200);
	}

    /**
     * Display a listing of post with id<requested.
     *
     * @return Response
     */
    public function infinite()
    {
        // Big default so we always get some posts
        $newest = Input::get('newest', 0) > 0 ? Input::get('newest') : 9999999999;
        $limit = Input::get('limit', 5);

        $posts = Post::orderBy('id', 'desc')->where('id', '<', $newest)->take($limit)->get();

        return Response::json([
            'data' => $posts->toArray()
        ], 200);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::json()->all();
        $v = Post::validate($input);

        if ( $v->passes() ) {
            $post = Post::create($input);
            return Response::json([
                'data' => $post->toArray()
            ], 201);

        } else {
            return Response::json([
                'errors' => $v->messages()->toArray(),
                'message' => 'Validation failed'
             ], 400);
        }
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Post::destroy($id);

        return Response::json([
            'success' => true
        ], 200);
	}

    public function search()
    {
        // TODO: add a fulltext index on title as well
        $query = Input::get('query');
        $tags = Input::get('tags');

        $posts = Post::join('tag_post', 'tag_post.post_id', '=', 'posts.id');

        if (Input::has('tags'))
            $posts->whereIn('tag_post.id', explode(',', $tags));
        if (Input::has('query'))
            $posts->whereRaw("MATCH(posts.body) AGAINST(?)", array($query));
        $posts->limit(50)->orderBy('created_at', 'desc');

        return Response::json([
          'data' => $this->postTransformer->transformCollection($posts->get()->toArray())
        ]);
    }

}
