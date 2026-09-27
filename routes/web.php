<?php

use App\Http\Controllers\AskJevController;
use App\Http\Controllers\SiteController;
use App\Jev\JevExamples;
use Illuminate\Support\Facades\Route;

Route::get('/', [AskJevController::class, 'create'])->name('jev.create');
Route::view('/faqs', 'jev.faqs')->name('jev.faqs');
Route::view('/what-is-jev', 'jev.what')->name('jev.what');
Route::view('/resources', 'jev.resources')->name('jev.resources');
Route::view('/lore', 'jev.lore')->name('jev.lore');
Route::get('/sitemap.xml', [SiteController::class, 'sitemap'])->name('jev.sitemap');
Route::get('/llms.txt', [SiteController::class, 'llms'])->name('jev.llms');
Route::get('/robots.txt', [SiteController::class, 'robots'])->name('jev.robots');
Route::post('/{type}', [AskJevController::class, 'store'])
    ->name('jev.store')
    ->whereIn('type', JevExamples::Types);
