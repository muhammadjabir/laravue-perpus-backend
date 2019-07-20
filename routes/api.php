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

Route::post('login','AuthController@login');

Route::middleware('auth:api')->group(function () {
	  Route::post('logout','AuthController@logout');
	  Route::resource('users','UserController');
	  Route::post('users/ubah','UserController@ubah');
	  Route::resource('peminjaman','PeminjamanController');

	  Route::resource('anggota','AnggotaController');
	  Route::resource('books','BookController');
});
