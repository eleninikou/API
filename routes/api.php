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

// http://localhost/storage/file.txt

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('logout','API\PassportController@logout'); 

    Route::get('projects/user/all', 'ProjectController@activeProjects');
    Route::get('projects/user', 'ProjectController@userProjects');
    Route::get('projects/team/{id}', 'ProjectController@team');
    Route::resource('projects', 'ProjectController')->except(['create', 'edit']);
    
    Route::post('projects/{id}/invite', 'InviteController@invite');
    Route::get('projects/{id}/invited', 'InviteController@usersInvited'); 
    Route::get('accept/{token}', 'InviteController@accept'); 
    
    Route::get('tickets/user', 'TicketController@userTickets');
    Route::post('tickets/image', 'TicketController@saveImage');
    Route::resource('tickets', 'TicketController')->except(['create', 'edit']);
    
    Route::resource('comments', 'CommentController')->except(['edit']);

    Route::resource('types', 'TicketTypeController')->except(['create', 'edit']);
    Route::resource('status', 'TicketStatusController')->except(['create', 'edit']);
    
    Route::get('milestones/project/{id}', 'MilestoneController@project');
    Route::resource('milestones', 'MilestoneController')->except(['create', 'edit']);

    Route::resource('roles', 'RoleController')->except(['create', 'edit']);
    Route::get('user', 'API\PassportController@details');
    Route::resource('users', 'UserController')->except(['create', 'edit']);

    Route::get('activity/user', 'ProjectActivityController@projectActivity');
    Route::resource('activity', 'UserController')->except(['create', 'edit', 'update', ]);
});





