<?php

namespace App\Http\Services;

class ArquivoService
{
    private $cepService;
    private $formatadorService;
    private $parcelasService;

    public function __construct(
        FormatadorService $formatadorService,
        CEPService $cepService,
        ParcelasService $parcelasService
    ) {
        $this->formatadorService = $formatadorService;
        $this->cepService = $cepService;
        $this->parcelasService = $parcelasService;
    }

    /**
     * Processa o conteúdo do arquivo, já convertido da base 64 para o formato
     * "normal". Se durante o processamento algum erro ocorrer em alguma linha,
     * esse método retorna false imediatamente.
     * 
     * @param string $conteudo linhas descriptografadas do arquivo
     * @return bool|array
     */
    public function processaConteudo(string $conteudo)
    {
        $resultado = [];

        $linhas = preg_split('/\R/', $conteudo);
        $numeroDeLinhas = count($linhas);
        $contadorDeLinhas = 0;

        while ($contadorDeLinhas < $numeroDeLinhas) {
            $linha = $linhas[$contadorDeLinhas++];
            $componentes = $this->decompoeLinha($linha);

            if (!$componentes) {
                return false;
            }

            $id = (int)$componentes[0];
            $dataFormatada = $this->formatadorService->formataData($componentes[1]);

            if (!$dataFormatada) {
                return false;
            }

            $valorTotalFormatado = $this->formatadorService->formataValorReal($componentes[2]);
            $nome = rtrim($componentes[4]);

            $endereco = $this->cepService->processaCEPEInterpretaEndereco($componentes[5]);

            if (!$endereco) {
                return false;
            }

            $parcelas = $this->parcelasService->calculaParcelas(
                $componentes[3],
                $valorTotalFormatado,
                $dataFormatada
            );

            $resultado[] = [
                'id' => $id,
                'date' => $dataFormatada,
                'amount' => $valorTotalFormatado,
                'customer' => [
                    'name' => $nome,
                    'address' => $endereco
                ],
                'installments' => $parcelas
            ];
        }

        return ['sales' => $resultado];
    }

    /**
     * Valida se a linha recebida está no formato correto (apenas em termos de
     * sintaxe, sem considerar a sua validade semântica). Se estiver, retorna as
     * partes da linha em um array. Retorna false se a sintaxe da linha estiver
     * incorreta.
     * 
     * @param string $linha linha atual do arquivo
     * @return bool|string
     */
    private function decompoeLinha(string $linha)
    {
        if (
            !preg_match(
                '/^(\d{3})(\d{4}\d{2}\d{2})(\d{10})(\d{2})((?:\w|\s){20})(\d{8})$/u',
                $linha,
                $resultado
            )
        ) {
            return false;
        }

        return array_slice($resultado, 1);
    }
}
