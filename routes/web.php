<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Basic\HomeController;

// Route::get('/', function () {
//     // return view('welcome');
//     return redirect()->route('filament.dashboard.pages.dashboard');
// });
// Routes for main domain

Route::middleware([ 'SetLocale'])->group(function () use ($router) {
    $router->get('/', [HomeController::class, 'index'])->name('home');
});


// $router->get('/login', fn() => redirect()->route('filament.admin.auth.login'))->name('login');
Route::get('lang/{lang}', function ($lang) {

    if (!in_array($lang, ['en', 'ar'])) {
        abort(404);
    }

    session(['locale' => $lang]);
    app()->setLocale($lang);

    return redirect()->back();
})->name('lang.switch');
