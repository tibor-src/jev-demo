<?php

use App\Http\Controllers\AskJevController;
use App\Jev\JevExamples;
use Illuminate\Support\Facades\Route;

Route::get('/', [AskJevController::class, 'create'])->name('jev.create');
Route::post('/{type}', [AskJevController::class, 'store'])
    ->name('jev.store')
    ->whereIn('type', JevExamples::Types);
