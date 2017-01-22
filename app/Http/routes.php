<?php
Route::get('/', 'RequestallController@index');
Route::post('/filter', 'RequestallController@filter');
Route::get('/list-fb', 'RequestallController@listFb');
Route::get('/list-query', 'RequestallController@listQuery');
Route::get('/copy-table-requestall-to-new-table', 'RequestallController@copyTblRequestAllToNewTbl');
Route::get('/saveLastStateOfTblRequesAllCopy', 'RequestallController@saveLastStateOfTblRequesAllCopy');

