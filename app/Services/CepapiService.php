<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CepapiService {
    public function getAddressByCep (string $cep, $returnsJson = false) {
        $response = Http::cep()->get("/{$cep}");
        if (!$returnsJson) {
            return $response;
        }
        return $response->json();
    }
}
