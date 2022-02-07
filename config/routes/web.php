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
use App\Core\Parsedown;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', ['WelcomeController@home', ['auth']]);
Route::get('/dashboard', ['DashboardController@index', ['auth']]);

Route::group(['prefix' => 'requestbook', 'middleware' => ['auth']], function () {
    Route::get('/', ['RequestBookController@index']);
    Route::post('/save', ['RequestBookController@save']);
    Route::post('/delete', ['RequestBookController@delete']);
    Route::post('/approve', ['RequestBookController@approve']);
    Route::get('/badgeCountSse', ['RequestBookController@badgeCounter']);
});

Route::group(['prefix' => 'mycalendar', 'middleware' => ['auth']], function () {
    Route::get('/', ['CalendarController@index']);
    Route::get('/tasks', ['CalendarController@tasks']);
    Route::post('/tasks/add', ['CalendarController@taskAdd']);
    Route::post('/tasks/detail', ['CalendarController@taskDetail']);
    Route::post('/search', ['CalendarController@search']);
    Route::post('/datas', ['CalendarController@data']);
    Route::post('/delete', ['CalendarController@delete']);
    Route::post('/update', ['CalendarController@update']);
});


Route::group(['prefix' => 'notebook', 'middleware' => ['auth']], function () {
    Route::get('/', ['NotebookController@index']);
    Route::post('/new', ['NotebookController@add']);
    Route::post('/save', ['NotebookController@save']);
    Route::post('/search', ['NotebookController@search']);
    Route::post('/datas', ['NotebookController@data']);
    Route::post('/delete', ['NotebookController@delete']);
    Route::post('/update', ['NotebookController@update']);
});

Route::group(['prefix' => 'project', 'middleware' => ['auth']], function () {
    Route::get('/', ['ProjectController@index']);
    Route::get('/{projectcode}', ['ProjectController@view']);
    Route::post('/add', ['ProjectController@store']);

    Route::group(['prefix' => '/task', 'middleware' => ['auth']], function () {
        Route::post('/', ['ProjectController@task']);
        Route::post('/update', ['ProjectController@updateTaskType']);
        Route::post('/detail', ['ProjectController@taskDetail']);
        Route::post('/update/details', ['ProjectController@taskUpdate']);
        Route::post('/add', ['ProjectController@taskAdd']);
        Route::post('/delete', ['ProjectController@taskDelete']);
        Route::post('/searchmember', ['ProjectController@taskSearchMember']);
        Route::post('/inviteMember', ['ProjectController@taskInviteMember']);
    });

    Route::group(['prefix' => '/settings', 'middleware' => ['auth']], function () {
        Route::get('/{code}', ['ProjectController@details']);
        Route::post('/update', ['ProjectController@update']);
        Route::post('/member/delete', ['ProjectController@memberDelete']);
        Route::post('/members', ['ProjectController@members']);
        Route::post('/delete', ['ProjectController@delete']);
        Route::post('/finish', ['ProjectController@finish']);
        Route::post('/saveInvite', ['ProjectController@saveInvite']);
        Route::post('/saveGroupInvite', ['ProjectController@saveGroupInvite']);
        Route::post('/searchPeople', ['ProjectController@searchPeople']);
    });
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

Route::get('/whatsnew', function () {
    $pd = new Parsedown();
    $mdContent = file_get_contents('app/views/whats_new/new.md');
    echo $pd->text($mdContent);
});

Route::get('/test', function () {
    dd(Route::uriCollection());
});
