<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SalesDataService
{
    private $rawData;
    private $httpClient;

    public function __construct(UploadedFile $uploadedFile, Client $httpClient)
    {
        $this->rawData = collect(file($uploadedFile->getRealPath()));
        $this->httpClient = $httpClient;
    }

    public function run()
    {
        return $this->extract()->interpret();
    }

    private function extract()
    {
        $this->rawData = $this->rawData->map(function($lineOfText) {
            return [
                'id_venda' => substr($lineOfText, 0, 3),
                'data_venda' => substr($lineOfText, 3, 8),
                'valor_venda' => substr($lineOfText, 11, 10),
                'numero_parcelas' => substr($lineOfText, 21, 2),
                'nome_cliente' => substr($lineOfText, 23, 20),
                'cep_comprador' => substr($lineOfText, 43, 8)
            ];
        });

        return $this;
    }

    private function interpret()
    {
        //
        // Passo 1 - Converter string para tipo de dado correspondente
        //
        $this->rawData = $this->rawData->map(function($entry) {
            return [
                'id_venda' => intval($entry['id_venda']),
                'data_venda' => date_create_from_format('Ymd', $entry['data_venda']),
                'valor_venda' => floatval(substr($entry['valor_venda'], 0, 8) . '.' . substr($entry['valor_venda'], 8, 2)),
                'numero_parcelas' => intval($entry['numero_parcelas']),
                'nome_cliente' => trim($entry['nome_cliente']),
                'cep_comprador' => $entry['cep_comprador']
            ];
        });

        //
        // Passo 2 - Busca os dados de endereço via api a partir do CEP
        //
        $this->rawData = $this->rawData->map(function($entry) {

            $address = $this->httpClient->request('GET', 'https://viacep.com.br/ws/' . $entry['cep_comprador'] . '//json/');
            $addressJson = json_decode($address->getBody());

            return [
                'id_venda' => $entry['id_venda'],
                'data_venda' => $entry['data_venda'],
                'valor_venda' => $entry['valor_venda'],
                'numero_parcelas' => $entry['numero_parcelas'],
                'nome_cliente' => $entry['nome_cliente'],
                'cep_comprador' => $entry['cep_comprador'],
                'address' => [
                    'street' => $addressJson->logradouro,
                    'neighborhood' => $addressJson->bairro,
                    'city' => $addressJson->localidade,
                    'state' => $addressJson->uf,
                    'postal_code' => $addressJson->cep
                ]
            ];
        });

        //
        // Passo 3 - Formata os dados de acordo com o padrão exigido (datas, valores numéricos, chaves em inglês, etc.)
        //
        $this->rawData = $this->rawData->map(function($entry) {
            return [
                'id' => $entry['id_venda'],
                'date' => date_format($entry['data_venda'], 'Y-m-d'),
                'amount' => $entry['valor_venda'],
                'customer' => [
                    'name' => $entry['nome_cliente'],
                    'address' => $entry['address']
                ],
                'installments' => collect(array_fill(0, $entry['numero_parcelas'], 0))->map(function($installment, $index) use($entry) {
                    return [
                        'installment' => ++$index,
                        'amount' => floatval(number_format($entry['valor_venda']/$entry['numero_parcelas'], 2)),
                        'date' => Carbon::parse($entry['data_venda'])->addDays(29 * ($index + 1))->format('Y-m-d')
                    ];
                })
            ];
        });

        return $this->rawData;
    }
}
