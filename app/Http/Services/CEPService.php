<?php

namespace App\Http\Services;

use Symfony\Component\HttpFoundation\Response;

class CEPService
{
    /**
     * Despacha o CEP para o método buscaCEP, e, se o endereço respectivo for
     * encontrado, envia o resultado para o método montaEndereco. Esse método
     * retorna false se o CEP não for encontrado.
     * 
     * @param string $cep cep do cliente
     * @return bool|array
     */
    public function processaCEPEInterpretaEndereco(string $cep)
    {
        $resultado = $this->buscaCEP($cep);

        if (!$resultado) {
            return false;
        }

        return $this->montaEndereco($resultado['body']);
    }

    /**
     * Busca o CEP fornecido via API. Retorna o corpo e o código HTTP da
     * resposta recebida, ou false, se ocorrer um erro.
     * 
     * @param string $cep cep do cliente
     * @return bool|array
     */
    private function buscaCEP(string $cep)
    {
        $curl = curl_init(env('CEP_API_BASE_URL') . "/ws/$cep/json");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resultado = json_decode(curl_exec($curl), true);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if (!$resultado || (array_key_exists('erro', $resultado) && $resultado['erro'])) {
            return false;
        }

        return [
            'status' => $status,
            'body' => $resultado
        ];
    }

    /**
     * Converte o resultado da busca do CEP via API para o formato a ser usado
     * na resposta final.
     * 
     * @param array $endereco resultado da busca do CEP via API
     * @return array
     */
    private function montaEndereco(array $endereco): array
    {
        $rua = $endereco['logradouro'];
        $bairro = $endereco['bairro'];
        $cidade = $endereco['localidade'];
        $estado = $endereco['uf'];
        $cepFormatado = $endereco['cep'];

        return [
            'street' => $rua,
            'neighborhood' => $bairro,
            'city' => $cidade,
            'state' => $estado,
            'postal_code' => $cepFormatado
        ];
    }
}
