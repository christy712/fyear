<?php

use App\Http\Controllers\HolidayController;

Route::get('/', [HolidayController::class, 'index'])->name('holidays');