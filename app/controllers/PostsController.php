<?php

class PostsController extends BaseApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return Response::json(Post::all());
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
        return Response::json($posts);
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
            Post::create($input);
            return Response::json(array('success' => true), 201);
        } else {
            return Response::json(array(
                'success' => false,
                'errors' => $v->messages()->toJson()), 400
            );
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

        return Response::json(array('success' => true));
	}

}
