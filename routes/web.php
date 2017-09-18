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

Route::get('/', function ()
{
    return redirect()->route('main');
});

Route::get('main', function ()
{
    return View('main');
})->name('main');

Route::get('about', function()
{
    return View('about');
});

Route::get('request/{page?}', 'MainController');

Route::get('db', 'dbTest');


