<?php

use Illuminate\Support\Facades\Route;
use Vladi\Activity\Http\Controllers\ActivityController;

Route::post('/activity', [ActivityController::class, 'handle']);
