<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;

Route::get('/', function () {
    return view('welcome');
});




Route::get('/translate', [TranslationController::class, 'index'])->name('translate.index');
Route::post('/translate', [TranslationController::class, 'translate'])->name('translate.translate');
