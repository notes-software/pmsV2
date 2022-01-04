<?php

// authentication
use App\Core\Routing\Route;

Route::post('/logout', ['AuthController@logout']);

Route::group(['prefix' => 'login'], function () {
    Route::get('/', ['AuthController@index']);
    Route::post('/', ['AuthController@store']);
});

Route::group(['prefix' => 'register'], function () {
    Route::get("/", ['RegisterController@index']);
    Route::post("/", ['RegisterController@store']);
});

Route::group(['prefix' => 'profile', 'middleware' => ['auth']], function () {
    Route::get("/", ['ProfileController@index']);
    Route::post('/{user_id}', ['ProfileController@update']);
    Route::post('/changepass', ['ProfileController@changePass']);
    Route::post('/delete/{user_id}', ['ProfileController@destroy']);
});

Route::group(['prefix' => 'forgot/password'], function () {
    Route::get("/", ['AuthController@forgotPassword']);
    Route::post("/", ['AuthController@sendResetLink']);
});

Route::group(['prefix' => 'reset/password'], function () {
    Route::get("/{id}", ['AuthController@resetPassword']);
    Route::post("/", ['AuthController@passwordStore']);
});
