<?php

use Illuminate\Support\Facades\Route;

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
})->name('index');

Auth::routes();

Route::get('/redirect', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('/callback', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/home/newlog', 'App\Http\Controllers\LogsController@newLog');
    Route::view('log', 'log')->name('log');
    Route::post('/log/delete', 'App\Http\Controllers\LogsController@cancelLog');
});

Route::prefix('staff')->middleware(['staff'])->group(function () {
    // Route::view('info', 'staff.info')->middleware('etag');
    // Route::view('participant', 'staff.participant')->middleware('etag');
    // Route::view('registration/medcamp', 'staff.registration-medcamp');
    // Route::view('registration/talk', 'staff.registration-talk');
    // Route::get('participant/id/{user}', 'StaffController@retrieveUser');
    // Route::post('participant/presence', 'StaffController@updatePresence');
    Route::view('edit', 'staff.edititem');
    Route::post('edit', 'App\Http\Controllers\ItemsController@updateItemInfo');
    Route::view('logmonitor', 'staff.approvelog');
    Route::post('logmonitor/approve', 'App\Http\Controllers\LogsController@approveLog');
    // Route::post('control/clear', 'StaffController@clearPresence');

    // Route::get('userResource', 'StaffController@userResource');
});