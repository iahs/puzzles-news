<?php

class UsersController extends BaseApiController {

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

    public function update()
    {
        $user_data = Input::json('data');
        if( ! $user_data || ! $user_data['id'] || ! Auth::check()){
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
}
