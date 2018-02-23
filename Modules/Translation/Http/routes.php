<?php

Route::group(['middleware' => 'web', 'prefix' => 'translation', 'namespace' => 'Modules\Translation\Http\Controllers'], function()
{
    Route::get('/', 'TranslationController@index');
});
