<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landing');
});

Route::post('/register', 'Auth\RegisterController@register')->name('register');
// Route::post('/oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken')->name('passport.token');

// Auth::routes();


Route::group(['prefix' => '/'], function () {
    Route::get('/{path?}', function () {
        return view('app');
    })->where('path', '.*');
});
