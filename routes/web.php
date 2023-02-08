<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})
->middleware('auth')
->name('dashboard');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/auth/login', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userData = $request->only([
            'email',
            'password',
        ]);

        if (Auth::attempt($userData)) {
            return redirect('/');
        }

        return redirect('dashboard');
    } catch (\Exception $exception) {
        return redirect('/login')
            ->withErrors('Invalid credentials');
    }
})->name('forms.login');

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/forgot-password', function () {
    return [
        'page' => 'forgot-password'
    ];
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::get('/reset-password/{token}', function (string $token) {
    return [
        'token' => $token,
    ];
})->name('password.reset');
