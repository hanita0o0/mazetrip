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

use App\Photo;
use App\User;
use App\Filter;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/admin',function (){
    return view ('admin.index');
});

Route::resource('/admin/user','AdminUserController');
Route::resource('/admin/type','AdminTypeController');
Route::resource('/admin/gallery','AdminGalleryController');





Route::resource('/admin/post','AdminPostController');



Route::patch('/admin/event/managers/{id}','AdminEventController@addManager');
Route::delete('/admin/event/managers/{eventid}/{userid}','AdminEventController@deleteManager');
Route::put('/admin/event/handlerequest/{request_id}','AdminEventController@handleRequest');
Route::post('/admin/event/createtimeline/','AdminEventController@createTimeline');
Route::delete('/admin/event/deletetimeline/{event_id}/{timeline_id}','AdminEventController@deleteTimeline');
Route::delete('/admin/event/deleteticket/{ticket_id}/{event_id}','AdminEventController@deleteTicket');
Route::put('/admin/event/createticket/{event_id}','AdminEventController@createTicket');
Route::resource('/admin/event','AdminEventController');



Route::get('/test',function (){
    return 'done';

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
