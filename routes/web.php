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

// Good luck finding docs for this command
Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/company/{id}', 'HomeController@employeeIndex');
Route::get('/home/managers', 'HomeController@managerIndex');

Route::resource("/companies", "CompanyController")->except(['create', 'edit']);
Route::resource("/employees", "EmployeeController")->except(['create', 'edit']);
Route::resource("/managers", "ManagerController")->except(['create', 'edit']);