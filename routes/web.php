<?php

use App\Http\Controllers\ScraperController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('scraper');
});

Route::post('/scrape', [ScraperController::class, 'scrape']);
Route::post('/download-csv', [ScraperController::class, 'downloadCSV']);