<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Budget Planner API is running!';
});
Route::get('/page2', function () {
    return 'This is test page 2';
});
