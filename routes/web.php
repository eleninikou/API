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
    return view('welcome');
});

// Auth::routes();

// Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
// Route::get('/callback/google', 'Auth\LoginController@handleProviderCallback');

Route::get('/home', 'HomeController@index')->name('home');

// Route::get('projects/user', 'ProjectController@userProjects');
// Route::resource('projects', 'ProjectController')->except(['create', 'edit']);

// Route::get('invite', 'InviteController@invite')->name('invite');
// Route::post('invite', 'InviteController@process');
// Route::get('accept/{token}', 'InviteController@accept'); // {token} is a required parameter that will be exposed to us in the controller method


// Route::get('milestones/project/{id}', 'MilestoneController@project');
// Route::resource('milestones', 'MilestoneController')->except(['create', 'edit']);

// Route::get('tickets/user', 'TicketController@userTickets');
// Route::resource('tickets', 'TicketController')->except(['create', 'edit']);

// Route::resource('status', 'TicketStatusController')->except(['create', 'edit']);
// Route::resource('types', 'TicketTypeController')->except(['create', 'edit']);
// Route::resource('roles', 'RoleController')->except(['create', 'edit']);
// Route::resource('users', 'UserController')->except(['create', 'edit']);







