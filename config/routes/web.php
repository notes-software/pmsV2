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
Route::post('/change-branch', function () {
    $request = Request::validate();

    if ($request['id'] > 0) {
        $br_name = getBranchName($request['id']);
        $_SESSION['system']['branch_id'] = $request['id'];
        $_SESSION['system']['branch_name'] = $br_name;
        echo 1;
    } else {
        echo 0;
    }
});

Route::group(['prefix' => 'branch', 'middleware' => ['auth']], function () {
    Route::get('/', ['BranchController@index']);
    Route::post('/save', ['BranchController@store']);
    Route::post('/view/{id}', ['BranchController@edit']);
    Route::post('/update', ['BranchController@update']);
    Route::post('/delete', ['BranchController@destroy']);
});

Route::group(['prefix' => 'employee', 'middleware' => ['auth']], function () {
    Route::get('/', ['EmployeeController@index']);
    Route::get('/create', ['EmployeeController@create']);
    Route::post('/save', ['EmployeeController@store']);
    Route::get('/view/{id}', ['EmployeeController@edit']);
    Route::post('/update/{id}', ['EmployeeController@update']);
    Route::post('/delete', ['EmployeeController@destroy']);

    Route::group(['prefix' => '/position', 'middleware' => ['auth']], function () {
        Route::get('/', ['EmployeeController@positionIndex']);
        Route::post('/save', ['EmployeeController@positionStore']);
        Route::post('/view/{id}', ['EmployeeController@positionEdit']);
        Route::post('/update', ['EmployeeController@positionUpdate']);
        Route::post('/delete', ['EmployeeController@positionDestroy']);
    });
});

Route::group(['prefix' => 'product', 'middleware' => ['auth']], function () {
    Route::get('/', ['ProductController@index']);
    Route::get('/create', ['ProductController@create']);
    Route::post('/save', ['ProductController@store']);
    Route::get('/view/{id}', ['ProductController@edit']);
    Route::post('/update/{id}', ['ProductController@update']);
    Route::post('/delete', ['ProductController@destroy']);

    Route::group(['prefix' => '/category', 'middleware' => ['auth']], function () {
        Route::get('/', ['ProductCategoryController@index']);
        Route::post('/save', ['ProductCategoryController@store']);
        Route::post('/view/{id}', ['ProductCategoryController@edit']);
        Route::post('/update', ['ProductCategoryController@update']);
        Route::post('/delete', ['ProductCategoryController@destroy']);
    });

    Route::group(['prefix' => '/unit', 'middleware' => ['auth']], function () {
        Route::get('/', ['ProductUnitController@index']);
        Route::post('/save', ['ProductUnitController@store']);
        Route::post('/view/{id}', ['ProductUnitController@edit']);
        Route::post('/update', ['ProductUnitController@update']);
        Route::post('/delete', ['ProductUnitController@destroy']);
    });
});

Route::group(['prefix' => 'supplier', 'middleware' => ['auth']], function () {
    Route::get('/', ['SupplierController@index']);
    Route::post('/save', ['SupplierController@store']);
    Route::post('/view/{id}', ['SupplierController@edit']);
    Route::post('/update', ['SupplierController@update']);
    Route::post('/delete', ['SupplierController@destroy']);
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

Route::group(['prefix' => 'receiving', 'middleware' => ['auth']], function () {
    Route::get('/', ['ReceivingController@index']);
    Route::get('/create', ['ReceivingController@create']);
    Route::post('/displayReceivedItems', ['ReceivingController@displayReceivedItems']);
    Route::post('/displayPoItems', ['ReceivingController@displayPoItems']);
    Route::post('/receiveItem', ['ReceivingController@receiveItem']);
    Route::post('/removeReceivedItem', ['ReceivingController@removeReceivedItem']);
    Route::get('/view/{id}', ['ReceivingController@edit']);
    Route::post('/finish', ['ReceivingController@finish']);
    Route::get('/print/{id}', ['ReceivingController@rrPrint']);
});

Route::group(['prefix' => 'repack', 'middleware' => ['auth']], function () {
    Route::get('/', ['RepackController@index']);
    Route::get('/create', ['RepackController@create']);
    Route::post('/finish', ['RepackController@store']);
    Route::post('/compute', ['RepackController@computeQty']);
    Route::get('/view/{id}', ['RepackController@edit']);
    Route::post('/edit/finish', ['RepackController@update']);
});

Route::group(['prefix' => 'report', 'middleware' => ['auth']], function () {
    Route::get('/inventory', ['InventoryController@index']);
    Route::post('/inventory', ['InventoryController@generate']);
    Route::get('/salesReport', ['SalesController@reportIndex']);
    Route::post('/salesReport', ['SalesController@reportGenerate']);
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

Route::group(['prefix' => 'salesTransact', 'middleware' => ['auth']], function () {
    Route::get('/', ['SalesController@index']);
    // Route::get('/create', ['SalesController@create']);
    // Route::post('/displayReceivedItems', ['SalesController@displayReceivedItems']);
    // Route::post('/displayPoItems', ['SalesController@displayPoItems']);
    // Route::post('/receiveItem', ['SalesController@receiveItem']);
    // Route::post('/removeReceivedItem', ['SalesController@removeReceivedItem']);
    // Route::get('/view/{id}', ['SalesController@edit']);
    // Route::post('/finish', ['SalesController@finish']);
    // Route::get('/print/{id}', ['SalesController@rrPrint']);
});

Route::get('/test', function () {
    dd(Route::uriCollection());
});
