<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;

class Helper{

    public static function getAddress($cep){
        $cepRequest = Http::get("https://viacep.com.br/ws/$cep/json/");
        return $cepRequest->json();  
    }
}
    