<?php

Route::post('/shorten', 'ApiController@shorten');

Route::get('/urls', 'ApiController@listUrls');