<?php

/*
|--------------------------------------------------------------------------
| Angular Home Page
|--------------------------------------------------------------------------
|
| The client itself is in the public folder together with the AngularJS views.
|
*/

Route::get('/', function()
{
	return View::make('index');
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register all API routes in the API namespace
|
*/
Route::group(array('prefix' => 'api'), function() {
    // Angular will handle create and edit
    Route::resource('posts', 'PostsController',
        array('only' => array('index', 'store', 'destroy'))
    );
    // Infinite scrolling
    Route::get('posts/infinite', 'PostsController@infinite');
});

/*
|--------------------------------------------------------------------------
| Catchall
|--------------------------------------------------------------------------
|
| All routes that are not index or api will be redirected to Angular
|
*/
App::missing(function($exception)
{
    return View::make('index');
});

