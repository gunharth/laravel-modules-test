<?php

Route::group(['middleware' => 'web', 'prefix' => 'stub', 'namespace' => 'Modules\Stub\Http\Controllers'], function()
{
    Route::get('/', 'StubController@index');
});
