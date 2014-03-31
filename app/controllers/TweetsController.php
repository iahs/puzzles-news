<?php


class TweetsController extends BaseApiController
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tweets = Tweet::take(15)->get();

        return Response::json([
            'data' => $tweets->toArray()
        ], 200);
    }


}
