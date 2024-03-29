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

Route::namespace('Api')->middleware(['auth:api'])->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('bookmarks/exists', 'BookmarksController@exists');
    Route::post('bookmarks', 'BookmarksController@store');
    Route::post('bookmarks/import', 'BookmarksController@import');
    Route::get('user/tags', 'TagsController@getUserTags');
    Route::get('user/bookmarks', 'BookmarksController@get');
});
