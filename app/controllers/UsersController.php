<?php

class UsersController extends BaseApiController {

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->beforeFilter('userRequired', array('only' => array('update')));
    }

	/**
	 * Create a new user
	 *
	 * @return Response
	 */
	public function store()
	{
        $credentials = Input::json('data');

        $v = User::validate($credentials);

        if ( $v->passes() ) {
            $user = new User;
            $user->email = $credentials['email'];
            $user->first_name = $credentials['first_name'];
            $user->last_name = $credentials['last_name'];
            $user->password = Hash::make($credentials['password']);
            $user->save();
            Auth::login($user);

            return Response::json([
                'data' => $user->toArray(),
                'message' => 'User created'
            ], 201);
        }

        else {
            return Response::json([
                'errors' => $v->messages()->toArray(),
                'message' => 'Validation failed'
            ], 400);
        }
    }

    public function updateOld()
    {
        $user_data = Input::json('data');
        if( ! $user_data || ! array_key_exists('id', $user_data) || ! Auth::check()){
            return Response::json([
                'errors' => 'Invalid data or not authorized',
                'message' => 'You are not allowed to perfom this operation'
            ]);
        }
        $user = Auth::user();

        if ($user->tweeter_id) {
            $tweeter = Tweeter::find($user->tweeter_id);
            $tweeter->handle = $user_data['handle'];
            $tweeter->save();
        }
        else {
            $tweeter = new Tweeter;
            $tweeter->name = $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->cs50fullname;
            $tweeter->handle = $user_data['handle'];
            $tweeter->save();
            $user->tweeter_id = $tweeter->id;
        }
        $user->save();
    }

    public function update()
    {
        // before_filter has already checked user is signed in
        $user = Auth::user();

        // update_data
        $user_data = Input::json('data');

        // check that current user matches id from form
        if (! $user_data || !array_key_exists('id', $user_data) || $user_data['id'] != $user->id ) {
            return Response::json([
                'errors' => 'You are not allowed to update this user',
                'message' => 'You are not allowed to update this user'
            ]);
        }

        // Set twitter handle (and save that)
        if (array_key_exists('handle', $user_data)) {
            $user->setTwitterHandle($user_data['handle']);
        }

        if (array_key_exists('email', $user_data)) {
            $user->email = $user_data['email'];
        }

        $validator = User::updateValidator($user_data, $user);
        if ($validator->fails()) {
            return Response::json([
                'message' => "validation failed",
                'errors' => $validator->messages()->toArray()
            ], 402);
        }

        // Input valid
        $user->update($user_data);

        if (array_key_exists("password", $user_data)) {
            $user->password = Hash::make($user_data['password']);
        }

        $user->save();

        return Response::json([
            'message' => "user updated",
            'data' => $user->toArray()
        ], 200);
    }
}
