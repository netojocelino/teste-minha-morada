<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CepapiService {
    public function getAddressByCep (string $cep) {
        $url = "https://brasilapi.com.br/api/cep/v1/$cep";

        return Http::get($url);
    }
}
