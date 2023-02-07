<?php

use App\Models as Model;
use App\Services\CepapiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::post('/user', function (Request $request) {
    $body = $request->only([
        "name",
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
        "name" => $body["name"],
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

    return response()
        ->json($body);
});
