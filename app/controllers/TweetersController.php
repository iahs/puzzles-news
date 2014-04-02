<?php


class TweetersController extends BaseApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $tweeters = Tweeter::take(500)->get();

        return Response::json([
            'data' => $tweeters->toArray()
        ], 200);
    }


}
