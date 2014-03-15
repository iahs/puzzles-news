<?php

/*
|--------------------------------------------------------------------------
| Angular Home Page
|--------------------------------------------------------------------------
|
| The client itself is in the public folder together with the AngularJS views.
|
*/

Route::get('/', function () {
    return View::make('index');
});
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| Register all API routes in the API namespace
|
*/
Route::group(array('prefix'=> 'session'), function () {
    Route::get('/cs50login', array('as' => 'cs50login', 'uses' => 'SessionsController@cs50login'));
    Route::get('/cs50return', array('as' => 'cs50return', 'uses' => 'SessionsController@cs50return'));
    Route::get('/show', 'SessionsController@show');
    Route::get('/destroy', 'SessionsController@destroy');
    Route::post('/', 'SessionsController@store');
    Route::post('/apilogin', 'SessionsController@apilogin');
    Route::get('/apilogin', 'SessionsController@apilogin');
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Register all API routes in the API namespace
|
*/
Route::group(array('prefix' => 'api'), function () {
    // Angular will handle create and edit
    Route::resource('posts', 'PostsController',
        array('only' => array('index', 'store', 'destroy'))
    );

    Route::resource('rssfeeds', 'RssFeedsController',
        array('only' => array('index', 'store', 'destroy'))
    );

    // Infinite scrolling
    Route::get('posts/infinite', 'PostsController@infinite');

    // Tags
    Route::get('tags', 'TagsController@index');

    // Click tracking
    Route::post('posts/click', 'PostsController@click');

    Route::resource('users', 'UsersController',
        array('only' => array('store','update'))
    );
});
/*
|--------------------------------------------------------------------------
| Catchall
|--------------------------------------------------------------------------
|
| All routes that are not index or api will be redirected to Angular
|
*/
App::missing(function ($exception) {
    return View::make('index');
});
