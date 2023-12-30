<?php

use Illuminate\Support\Facades\Route;
use App\Modules\JenisSurvey\Controllers\JenisSurveyController;

Route::controller(JenisSurveyController::class)->middleware(['web','auth'])->name('jenissurvey.')->group(function(){
	Route::get('/jenissurvey', 'index')->name('index');
	Route::get('/jenissurvey/data', 'data')->name('data.index');
	Route::get('/jenissurvey/create', 'create')->name('create');
	Route::post('/jenissurvey', 'store')->name('store');
	Route::get('/jenissurvey/{jenissurvey}', 'show')->name('show');
	Route::get('/jenissurvey/{jenissurvey}/edit', 'edit')->name('edit');
	Route::patch('/jenissurvey/{jenissurvey}', 'update')->name('update');
	Route::get('/jenissurvey/{jenissurvey}/delete', 'destroy')->name('destroy');
});