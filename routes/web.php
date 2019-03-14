<?php
use Illuminate\Http\Request;
use App\Mail\Invitation;
use App\Invite;

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



Route::get('mailable', function () {
    $invite = Invite::find(1);
    return new Invitation($invite);
});

Route::get('accept/{token}', 'InviteController@accept'); // {token} is a required parameter that will be exposed to us in the controller method




