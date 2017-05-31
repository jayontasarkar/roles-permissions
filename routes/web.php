<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/roles', function(Illuminate\Http\Request $request){
	$user = $request->user();
	$user->givePermissionTo('add employee', 'edit employee', 'delete employee', 'list employees');
	//dump($user->can('add employee'));
});
