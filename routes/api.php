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

Route::post('logout','API\PassportController@logout'); 
Route::post('login', 'API\PassportController@login');
Route::post('register', 'API\PassportController@register');
Route::post('google', 'API\PassportController@googleAuth');







Route::group(['middleware' => 'auth:api'], function(){
    Route::resource('projects', 'ProjectController')->except(['create', 'edit']);
    Route::get('projects/user/{id}/all', 'ProjectController@activeProjects');
    Route::get('projects/user/{id}', 'ProjectController@userProjects');
    Route::get('projects/team/{id}', 'ProjectController@team');
    
    Route::post('projects/{id}/invite', 'InviteController@invite');
    Route::get('accept/{token}', 'InviteController@accept'); 
    
    Route::get('tickets/user', 'TicketController@userTickets');
    Route::resource('tickets', 'TicketController')->except(['create', 'edit']);
    
    Route::resource('types', 'TicketTypeController')->except(['create', 'edit']);
    Route::resource('status', 'TicketStatusController')->except(['create', 'edit']);
    
    Route::get('milestones/project/{id}', 'MilestoneController@project');
    Route::resource('milestones', 'MilestoneController');
    Route::get('user', 'API\PassportController@details');
    Route::resource('roles', 'RoleController')->except(['create', 'edit']);
    Route::resource('users', 'UserController')->except(['create', 'edit']);
});





