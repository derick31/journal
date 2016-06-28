<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'HomeController@index');

    Route::auth();

    Route::get('/home', 'HomeController@index');
    
    Route::get('/tag/{tag}', 'HomeController@tag');

    Route::get('/profile/{user}', 'ProfileController@index');

    Route::post('/background', 'ProfileController@background');

    Route::get('/posts', 'ProfileController@listPosts');

    Route::get('/post/{post}/delete', 'PostController@destroy');

    Route::get('/post/{post}/privacy', 'PostController@privacy');

    Route::get('/calendar', 'ProfileController@calendar');

    Route::resource('post','PostController');

    Route::resource('post.comment','CommentController');

    Route::resource('post.like','LikeController');

});