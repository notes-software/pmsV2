<?php

/**
 * --------------------------------------------------------------------------
 * Routes
 * --------------------------------------------------------------------------
 * 
 * Here is where you can register routes for your application.
 * Now create something great!
 * 
 */

use App\Core\Routing\Route;
use App\Core\Request;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', ['WelcomeController@home', ['auth']]);
Route::get('/dashboard', ['DashboardController@index', ['auth']]);

Route::group(['prefix' => 'requestbook', 'middleware' => ['auth']], function () {
    Route::get('/', ['RequestBookController@index']);
    Route::post('/save', ['BranchController@store']);
    Route::post('/view/{id}', ['BranchController@edit']);
    Route::post('/update', ['BranchController@update']);
    Route::post('/delete', ['BranchController@destroy']);
});

Route::group(['prefix' => 'notebook', 'middleware' => ['auth']], function () {
    Route::get('/', ['NotebookController@index']);
    Route::get('/create', ['NotebookController@create']);
    Route::post('/save', ['NotebookController@store']);
    Route::get('/view/{id}', ['NotebookController@edit']);
    Route::post('/update/{id}', ['NotebookController@update']);
    Route::post('/delete', ['NotebookController@destroy']);
});

Route::group(['prefix' => 'project', 'middleware' => ['auth']], function () {
    Route::get('/', ['ProjectController@index']);
    Route::get('/{projectcode}', ['ProjectController@view']);
    Route::get('/create', ['ProjectController@create']);
    Route::post('/save', ['ProjectController@store']);
    Route::get('/view/{id}', ['ProjectController@edit']);
    Route::post('/update/{id}', ['ProjectController@update']);
    Route::post('/delete', ['ProjectController@destroy']);
});

Route::group(['prefix' => 'purchase', 'middleware' => ['auth']], function () {
    Route::get('/', ['PurchaseController@index']);
    Route::get('/create', ['PurchaseController@create']);
    Route::post('/additem', ['PurchaseController@addItem']);
    Route::post('/displayItems', ['PurchaseController@displayItems']);
    Route::post('/save', ['PurchaseController@store']);
    Route::get('/view/{id}', ['PurchaseController@edit']);
    Route::post('/update', ['PurchaseController@update']);
    Route::post('/delete', ['PurchaseController@destroy']);
    Route::post('/selectUnit', ['PurchaseController@selectUnit']);
    Route::post('/deleteItem', ['PurchaseController@deleteItem']);
    Route::get('/print/{id}', ['PurchaseController@poPrint']);
    Route::post('/finish', ['PurchaseController@finish']);
});

Route::group(['prefix' => 'settings', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => '/permission', 'middleware' => ['auth']], function () {
        Route::get('/', ['SettingsController@permissionIndex']);
        Route::post('/save', ['SettingsController@permissionStore']);
        Route::post('/view/{id}', ['SettingsController@permissionEdit']);
        Route::post('/update', ['SettingsController@permissionUpdate']);
        Route::post('/delete', ['SettingsController@permissionDestroy']);
    });

    Route::group(['prefix' => '/roles', 'middleware' => ['auth']], function () {
        Route::get('/', ['SettingsController@rolesIndex']);
        Route::post('/save', ['SettingsController@rolesStore']);
        Route::post('/view/{id}', ['SettingsController@rolesEdit']);
        Route::post('/update', ['SettingsController@rolesUpdate']);
        Route::post('/delete', ['SettingsController@rolesDestroy']);
    });

    Route::group(['prefix' => '/users', 'middleware' => ['auth']], function () {
        Route::get('/', ['SettingsController@userIndex']);
        Route::post('/save', ['SettingsController@userStore']);
        Route::post('/view/{id}', ['SettingsController@userEdit']);
        Route::post('/update', ['SettingsController@userUpdate']);
        Route::post('/delete', ['SettingsController@userDestroy']);
    });
});

Route::get('/test', function () {
    dd(Route::uriCollection());
});
