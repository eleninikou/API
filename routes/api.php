<?php

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
Route::get('login',  function() {
    return 'login';
})->name('login');
Route::post('logout','API\PassportController@logout'); 
Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');
Route::post('google', 'API\PassportController@googleAuth');
Route::group(['middleware' => 'cors'], function(){
});

Route::get('accept/{token}', 'InviteController@accept'); // {token} is a required parameter that will be exposed to us in the controller method

Route::get('projects/user/{id}/all', 'ProjectController@activeProjects');
Route::get('projects/user/{id}', 'ProjectController@userProjects');
Route::get('tickets/user/{id}/all', 'TicketController@userTickets');
Route::resource('projects', 'ProjectController')->except(['create', 'edit']);

Route::group(['middleware' => 'auth:api'], function(){
    
    Route::get('user', 'API\PassportController@getDetails');
    
        
    
    Route::post('projects/{id}/invite', 'InviteController@invite');

    Route::get('milestones/project/{id}', 'MilestoneController@project');

    Route::resource('milestones', 'MilestoneController')->except(['create', 'edit']);
    Route::resource('tickets', 'TicketController')->except(['create', 'edit']);
    Route::resource('status', 'TicketStatusController')->except(['create', 'edit']);
    Route::resource('types', 'TicketTypeController')->except(['create', 'edit']);
    Route::resource('roles', 'RoleController')->except(['create', 'edit']);
    Route::resource('users', 'UserController')->except(['create', 'edit']);
});





