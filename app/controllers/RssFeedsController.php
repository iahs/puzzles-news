<?php

class RssFeedsController extends BaseApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $feeds = RssFeed::all();

        return Response::json([
            'data' => $feeds->toArray()
        ], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
    // TODO: Make sure the user is authorised to add a feed
	public function store()
	{
        $input = Input::json('data');
        if (! $input) $input = [];

        $v = RssFeed::validate($input);

        if ( $v->passes() ) {
            $feed = RssFeed::create($input);

            return Response::json([
                'data' => $feed->toArray()
            ], 201);

        } else {
            return Response::json([
                'errors' => $v->messages()->toArray(),
                'message' => 'Validation failed'
            ], 400);
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
