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
    Route::get('/kuru', [App\Http\Controllers\KuruController::class, 'index'])->name('kuru');
    Route::get('/search', [App\Http\Controllers\KuruController::class, 'search'])->name('search');
    Route::post('/kuru/save', [App\Http\Controllers\KuruController::class, 'save'])->name('save');
    //Route::view('termsandconditions', 'termsandconditions');
    Route::post('/home/agree', 'App\Http\Controllers\UsersController@agree' ); //
    Route::post('/home/newlog', 'App\Http\Controllers\LogsController@newLog');
    Route::view('log', 'log')->name('log');
    Route::post('/log/delete', 'App\Http\Controllers\LogsController@cancelLog');
});

Route::prefix('staff')->middleware(['staff'])->group(function () {
    Route::post('/save-kuru', [App\Http\Controllers\KuruController::class, 'savekuru'])->name('addone');
    Route::get('/download-excel', [App\Http\Controllers\KuruController::class, 'download'])->name('download.excel');
    Route::post('/kuru/addfromfile', [App\Http\Controllers\KuruController::class, 'addfromfile'])->name('addfromfile');
    Route::view('edit', 'staff.edititem');
    Route::view('kuru/add', 'staff.addkuru');
    Route::post('edit', 'App\Http\Controllers\ItemsController@updateItemInfo');
    Route::view('logmonitor', 'staff.approvelog');
    Route::post('logmonitor/approve', 'App\Http\Controllers\LogsController@approveLog');
});
