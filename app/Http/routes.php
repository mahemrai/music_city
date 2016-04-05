<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', 'App\AppController@homePage');
Route::post('results', 'App\AppController@results');

Route::get('artists', 'App\AppController@listArtists');
Route::delete('artists/{artistId}/delete', 'App\ArtistController@deleteArtist');
Route::get('artists/{artistId}', 'App\ArtistController@artistInfo');
Route::post('artists/{artistId}', 'App\ArtistController@addArtist');
Route::get('artists/{artistId}/albums', 'App\AppController@findAlbums');
Route::get('artists/{artistId}/albums/{albumId}', 'App\AlbumController@albumInfo');
Route::post('artists/{artistId}/albums/{albumId}', 'App\AlbumController@addAlbum');

Route::get('albums', 'App\AppController@listAlbums');

Route::get('api/artist/{artist}/similar', 'App\LastfmController@similarArtists');
Route::patch('artists/{artistId}/favourite', 'Api\ArtistController@markAsFavourite');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
