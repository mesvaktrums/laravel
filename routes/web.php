<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-vtiger', function () {
    $user = \App\Models\User::find(1); // Adjust the user ID accordingly
    $user->name = 'Smitss2s3s2'; // Modify some data to trigger the observer
    $user->save();
    return 'User updated and vTiger should be notified!';
});

Auth::routes();
Route::get('/send-test-email', [EmailController::class, 'sendTestEmail']);

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
