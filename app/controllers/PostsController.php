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

        $tagIds = json_decode('[' . $tags . ']', true);

        $idObjects = DB::table('tag_post')
            ->join('posts', 'posts.id', '=', 'tag_post.post_id')
            ->select('posts.id')
            ->distinct();

        if (Input::has('query'))
            $idObjects->whereRaw("MATCH(posts.body) AGAINST(?)", array($query));
        if (Input::has('tags'))
            $idObjects->whereIn('tag_post.tag_id', $tagIds, 'and');

        $idObjects = $idObjects->get();

        // dd(DB::getQueryLog());

        $postIds = [];
        foreach ($idObjects as $post) {
            if (! in_array($post->id, $postIds))
                array_push($postIds, $post->id);
        }

        $posts = Post::whereIn('id', $postIds, 'or')->get();

        return Response::json([
          'data' => $this->postTransformer->transformCollection($posts->toArray())
        ]);

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
