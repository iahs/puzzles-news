<?php

class SessionsController extends \BaseApiController {

    function __construct()
    {
        $this->cs50id_trust_root = Config::get('cs50id.trust_root');
        $this->cs50id_return_to = Config::get('cs50id.return_to');
    }

    /**
     * Create a new session when a user logs in with username and password
     * For standard post requests, now angular json format
     * @return Responses
     */
    public function store()
    {
        $credentials = ['email' => Input::get('email'), 'password' => Input::get('password')];

        If ( $credentials && Auth::attempt($credentials) ) {
           return Redirect::to('/')->with('message', 'Login success');
        }

        else {
            return Response::json([
                'errors' => 'Incorrect email or password',
                'debug' => $credentials,
                'message' => 'Incorrect email or password'
            ], 401);
        }
    }

    public function apiLogin()
    {
        $credentials = Input::json('data');

        If ( $credentials && Auth::attempt($credentials) ) {


            # For some unexplainable reason, logging in the user a second time and then returning status code 201
            # makes the login work
            # TODO: find out why - result: it is not necessary after all. Weird
            # $user = Auth::getUser($credentials);
            # Auth::login($user);
            return Response::json([
                'data' => Auth::user()->toArray(),
                'message' => 'You are now signed in'
            ], 200);
        }

        else {
            return Response::json([
                'errors' => 'Incorrect email or password',
                'message' => 'Incorrect email or password'
            ], 401);
        }
    }

    /**
     * Create a new session when user logs in through the
     * cs50id openid system
     * User will leave the page and then be redirected to the cs50return route
     * @return Response
     */
    public function cs50login()
    {
        return Response::make('', 302)->header(
            'location', CS50::getLoginUrl($this->cs50id_trust_root, $this->cs50id_return_to)
        );
    }

    /**
     * Handle the return from cs50login
     */
    public function cs50return()
    {
        $response = CS50::getUser($this->cs50id_return_to);
        # TODO: REDIRECT TO LOGIN IF FAILED (e.g. reloading, cs50 error, ...)
        if ($response)
        {
            $user = User::firstOrNew(array('cs50id' => $response['identity']));
            $user->cs50fullname = $response['fullname'];
            $user->cs50email = $response['email'];
            $user->save();
            Auth::login($user);

            return Redirect::to('/');
        }
        else
        {
            // The user refreshed the page, or went back in history.
            // Not a valid login
            return "No user returned";
        }
    }

    /**
     * Check if a user is signed in
     * If true, return user data and role
     * Otherwise, return 401 error message
     * @return Response
     */
    public function show()
    {
        if (Auth::check())
        {
            return Response::json([
                'authenticated' => true,
                'data' => Auth::user()->toArray()
            ], 200);
        } else {
            return Response::json([
                'authenticated' => false,
                'data' => [],
                'message' => 'Not authenticated',
                'errors' => ['authentication' => 'Not authenticated']
            ], 200);
        }

    }
    /**
     * Destroy the current session
     * This will log out the user
     */
    public function destroy()
    {
        Auth::logout();
        return Response::json([
            'message' => 'You are now signed out'
        ], 201);
    }

}