<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::get('articles', 'ArticleController@getArticles');
Route::get('article/{id}', 'ArticleController@getArticle');

Route::group(['middleware' => ['jwt.verify']], function() {
  Route::get('user', 'UserController@getAuthenticatedUserResponse');
  Route::post('articles', 'ArticleController@createArticle');
  Route::put('article/{id}', 'ArticleController@editArticle');
  Route::delete('article/{id}', 'ArticleController@deleteArticle');
});

