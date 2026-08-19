<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);

Route::get('/{path?}', PageController::class)
    ->where('path', '^(?!api(?:/|$)|up$|robots\.txt$|sitemap\.xml$|build(?:/|$)|assets(?:/|$)).*')
    ->name('app');
