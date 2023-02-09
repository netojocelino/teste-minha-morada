<?php

use App\Http\Requests as Validate;
use App\Models as Model;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

Route::post('/auth/login', function (Validate\LoginRequest $request) {
    try {
        $userData = $request->safe([
            'email',
            'password',
        ]);

        if (Auth::attempt($userData)) {
            return redirect('/');
        }

        return back()->withErrors('Credenciais inválidas.');
    } catch (\Exception $exception) {
        return redirect('/login')
            ->withErrors('Credenciais inválidas.');
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

    Auth::attempt([
        'email' => $body['email'],
        'password' => $body['password'],
    ]);

    return redirect('/')
        ->with([ 'message' => 'Criado com sucesso.' ]);
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

Route::post('/reset-password', function (Request $request) {
    try {
        $validate = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate->errors());
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with([ 'message' => __($status) ])
                : back()->withErrors(['email' => [__($status)]]);

    } catch (\Exception $exception) {
        return back()->withErrors([
            'error' => $exception->getMessage()
        ]);
    }
})->name('password.update');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? redirect('/login')->with(['message' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');


Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
});
