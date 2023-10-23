<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\FormController;

Route::get('/', [FormController::class, 'index'])->name("form.view");
Route::post("/submit", [FormController::class, 'submit'])->name("form.submit");
