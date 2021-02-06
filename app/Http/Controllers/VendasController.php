<?php

namespace App\Http\Controllers;

use App\Http\Services\ArquivoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendasController extends Controller
{
    private $arquivoService;
    private const MENSAGEM_ERRO =
        'Favor fornecer no corpo da requisição o conteúdo (codificado em ' .
        'base64) de um arquivo de vendas, em que cada linha é formada pelas ' .
        'seguintes partes: colunas 01 a 03: ID da venda; colunas 04 a 11: ' .
        'data da venda (YYYYMMDD); colunas 12 a 21: valor da venda (dois ' .
        'últimos dígitos como parte decimal); colunas 22 a 23: número de ' .
        'parcelas da venda; colunas 24 a 43: nome do cliente; colunas 44 a ' .
        '51: CEP do comprador.';

    public function __construct(ArquivoService $arquivoService)
    {
        $this->arquivoService = $arquivoService;
    }

    /**
     * Método associado à rota /arquivo/processar. Se a requisição recebida
     * possuir um corpo e se esse corpo estiver na chave "arquivo", e, ainda,
     * se for possível processar todas as linhas com sucesso, esse método
     * retorna o resultado no formato especificado. Se uma dessas condições
     * não for satisfeita, a mensagem de erro da constante MENSAGEM_ERRO é
     * retornada.
     * 
     * @param Request $requisicao requisição feita pelo cliente
     * @return JsonResponse
     */
    public function processaArquivo(Request $requisicao): JsonResponse
    {
        if (!$requisicao->has('arquivo')) {
            return response()->json(
                self::MENSAGEM_ERRO,
                Response::HTTP_BAD_REQUEST
            );
        }

        $conteudoArquivo = base64_decode($requisicao->input('arquivo'));
        $corpoResposta = $this->arquivoService->processaConteudo($conteudoArquivo);
        $statusResposta = Response::HTTP_OK;

        if ($corpoResposta === false) {
            $corpoResposta = self::MENSAGEM_ERRO;
            $statusResposta = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($corpoResposta, $statusResposta);
    }
}
