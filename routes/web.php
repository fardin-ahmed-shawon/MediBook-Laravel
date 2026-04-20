<?php

use Illuminate\Support\Facades\Route;

// This catch-all route forces Laravel to pass routing fully to Vue Router,
// except for routes defined in api.php
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
