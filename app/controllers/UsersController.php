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

}