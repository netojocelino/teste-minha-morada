<?php

use App\Models as Model;
use App\Services\CepapiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/cep', function (Request $request) {
    try {
        $cep = $request->query('cep', null);

        if (is_null($cep)) {
            return response()
                ->json([
                    'invalid' => 'CEP cannot be null',
                ], 422);
        }

        $match = "/[0-9]{5}-?[0-9]{3}/";
        if (!preg_match($match, $cep)) {
            return response()
                ->json([
                    'invalid' => 'CEP with invalid format',
                ], 422);
        }

        $service = new CepapiService();
        $response = $service->getAddressByCep($cep, true);

        $address = [
            "cep" => $response["cep"] ?? $cep,
            "state" => $response["state"],
            "city" => $response["city"],
            "neighborhood" => $response["neighborhood"] ?? '--',
            "street" => $response["street"] ?? '--',
            "service" => $response['service'],
        ];

        return response()->json($address);
    } catch (\Exception $err) {
        return response($err->getMessage(), 500);
    }
});

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return response()
        ->json([
            'email' => $request->email,
            'status' => $status,
        ]);
})->name('password.email');



Route::post('/reset-password', function (Request $request) {
    try {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

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

        return response()
            ->json([
                'status' => $status,
                'email' => $request->email,
            ]);
    } catch (\Exception $exception) {
        return response($exception->getMessage(), 500);
    }
})->name('password.update');
