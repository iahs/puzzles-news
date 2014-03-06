<?php

use Helpers\Transformers\PostTransformer;
use Illuminate\Support\Facades\Response;

class PostsController extends BaseApiController
{
    /**
     * @var Acme\Transformers\PostTransformerPostTransformer
     */
    protected $postTransformer;

    public function __construct(PostTransformer $postTransformer)
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
        $oldest = Input::get('oldest', 0) > 0 ? Input::get('oldest') : time();
        $oldest = date('Y-m-d H:i:s',$oldest);
        $limit = Input::get('limit', 5);

        $posts = Post::orderBy('time_posted', 'desc')->where('time_posted', '<', $oldest)->take($limit)->get();

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
     * @param  int      $id
     * @return Response
     */
    public function update($id)
    {
        //
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $query = Input::get('query');
        $tags = Input::get('tags');

        // Convert comma separated string to array of integer tag ids
        $tagIds = array_map("intval", explode(",", $tags));

        $idObjects = DB::table('tag_post')->join('posts', 'posts.id', '=', 'tag_post.post_id');

        if (Input::has('query'))
            $idObjects->whereRaw("MATCH(posts.title, posts.body) AGAINST(?)", array($query));
        if (Input::has('tags'))
            $idObjects->whereIn('tag_post.tag_id', $tagIds, 'and');

        // Get the ids of all posts that match the query and tag ids
        $idObjects = $idObjects->select('posts.id as postid')->distinct()->get();

        // Convert array of stdObj to an integer array for the wherein query
        $postIds = [];
        foreach ($idObjects as $post) {
            if (! in_array($post->postid, $postIds))
                array_push($postIds, $post->postid);
        }

        if (count($postIds)) {
            // Find all posts that corresponds to the query string and supplied tag ids
            $posts = Post::whereIn('id', $postIds, 'or')->orderBy('created_at')->get();
        } else {
            // Error message if whereIn is used with empty array
            // This query will always return empty, as no posts can have negative id
            $posts = Post::where('id', '=', -1)->get();
        }

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
