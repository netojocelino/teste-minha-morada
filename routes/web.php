<?php

use App\Models as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
    return view('home', [
        'users' => Model\User::paginate(),
    ]);
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

        return redirect('/');
    } catch (\Exception $exception) {
        return redirect('/login')
            ->withErrors('Invalid credentials');
    }
})->name('forms.login');

Route::get('/signup', function () {
    return view('signup');
})->name('register');

Route::post('/register', function (Request $request) {
    $validator = Validator::make($request->all(), [
        "full_name" => 'required',
        "email" => 'required|email|unique:users,email',
        "password" => 'required|confirmed',
        "address.*" => 'required|array',
        "address.cep" => 'required|regex:/[0-9]{5}-?[0-9]{3}/',
        "address.state" => 'required',
        "address.number" => 'required',
        "address.city" => 'required',
        "address.neighborhood" => 'required',
        "address.street" => 'required',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator->errors()->all());
    }

    $body = $request->only([
        "full_name",
        "email",
        "password",
        "address.cep",
        "address.state",
        "address.number",
        "address.city",
        "address.neighborhood",
        "address.street",
    ]);

    $userModel = new Model\User([
        "name" => $body["full_name"],
        "email" => $body["email"],
        "password" => Hash::make($body["password"]),
    ]);

    $userModel->save();

    $addressModel = new Model\Address([
        "cep" => $body['address']['cep'],
        "state" => $body['address']['state'],
        "number" => $body['address']['number'],
        "city" => $body['address']['city'],
        "neighborhood" => $body['address']['neighborhood'],
        "street" => $body['address']['street'],
        "user_id" => $userModel->id,
    ]);

    $addressModel->save();

    return response()->json($userModel);
})->name('register.action');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/reset-password/{token}', function (string $token, Request $request) {
    return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
})->name('password.reset');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});
