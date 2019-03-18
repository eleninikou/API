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

Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');
Route::post('google', 'API\PassportController@googleAuth');

Route::get('accept/{token}', 'InviteController@accept'); // {token} is a required parameter that will be exposed to us in the controller method


Route::group(['middleware' => 'auth:api'], function(){
    
    Route::get('user', 'API\PassportController@getDetails');
    Route::post('logout','API\PassportController@logout'); 
    
    Route::get('projects/user', 'ProjectController@userProjects');
    Route::resource('projects', 'ProjectController')->except(['create', 'edit']);
    Route::post('projects/{id}/invite', 'InviteController@invite');
    
    Route::get('milestones/project/{id}', 'MilestoneController@project');
    Route::resource('milestones', 'MilestoneController')->except(['create', 'edit']);
    
    Route::get('tickets/user', 'TicketController@userTickets');
    Route::resource('tickets', 'TicketController')->except(['create', 'edit']);
    
    Route::resource('status', 'TicketStatusController')->except(['create', 'edit']);
    Route::resource('types', 'TicketTypeController')->except(['create', 'edit']);
    Route::resource('roles', 'RoleController')->except(['create', 'edit']);
    Route::resource('users', 'UserController')->except(['create', 'edit']);
});





