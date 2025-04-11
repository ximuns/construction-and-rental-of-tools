<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'] )->name('home');
Route::get('/portfolio', [\App\Http\Controllers\PortfolioController::class, 'index'])->name('page.portfolio');
Route::get('/rent', [\App\Http\Controllers\RentController::class, 'index'])->name('page.rent');

Route::get('/test' , function () {
    return \App\Models\Service::all();
});
