<?php
Route::get('/', 'RequestallController@index');
Route::post('/filter', 'RequestallController@filter');
Route::get('/list-fb', 'RequestallController@listFb');
Route::get('/list-query', 'RequestallController@listQuery');
