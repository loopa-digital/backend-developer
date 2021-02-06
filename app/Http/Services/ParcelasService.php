<?php

namespace App\Http\Services;

class ParcelasService
{
    /**
     * Calcula o número, o valor e a data de vencimento de cada parcela
     * referente à venda. O dia de vencimento "base" é o dia do mês da compra,
     * e cada parcela deve vencer nesse mesmo dia, exceto se cair em um sábado
     * ou em um domingo - nesse caso, o vencimento é "deslocado" para a
     * segunda-feira seguinte.
     * 
     * @param int $numeroDeParcelas número total de parcelas da venda
     * @param float $valorTotal valor total da venda
     * @param string $dataVenda data em que a venda ocorreu
     */
    public function calculaParcelas(
        int $numeroDeParcelas,
        float $valorTotal,
        string $dataVenda
    ): array {
        $valorParcela = round($valorTotal / $numeroDeParcelas, 2);

        $parcelas = [];

        $partesDataVenda = explode('-', $dataVenda);
        $ano = $anoInicial = (int)$partesDataVenda[0];
        $mes = $mesInicial = (int)$partesDataVenda[1];
        $dia = $diaInicial = (int)$partesDataVenda[2];

        for (
            $contadorDeParcelas = 0;
            $contadorDeParcelas < $numeroDeParcelas ;
            ++$contadorDeParcelas, $dia = $diaInicial
        ) {
            if ($mes < 12) {
                ++$mes;
            } else {
                $mes = 1;
                ++$ano;
            }

            $anoFormatado = str_pad($ano, 4, '0', STR_PAD_LEFT);
            $mesFormatado = str_pad($mes, 2, '0', STR_PAD_LEFT);
            $diaFormatado = str_pad($dia, 2, '0', STR_PAD_LEFT);

            // Procura pelo dia útil seguinte mais próximo do dia "base" da data de compra:
            while (
                ($diaInvalido = !checkdate($mes, $dia, $ano)) ||
                ($fimDeSemana = (date('N', strtotime("$anoFormatado-$mesFormatado-$diaFormatado")) >= 6))
            ) {
                if ($diaInvalido) {
                    --$dia;
                } else {
                    ++$dia;
                }
                
                $diaFormatado = str_pad($dia, 2, '0', STR_PAD_LEFT);
            }

            $diaFormatado = str_pad($dia, 2, '0', STR_PAD_LEFT);

            $vencimento =
                $anoFormatado . '-' .
                $mesFormatado . '-' .
                $diaFormatado;

            $parcelas[] = [
                'installment' => $contadorDeParcelas + 1,
                'amount' => round($valorParcela, 2),
                'date' => $vencimento
            ];
        }

        $valorTotalRecalculado = round($numeroDeParcelas * $valorParcela, 2);

        // Correção de valor a mais ou a menos:
        if ($valorTotalRecalculado < $valorTotal) {
            $parcelas[0]['amount'] += round($valorTotal - $valorTotalRecalculado, 2);
        } else if ($valorTotalRecalculado > $valorTotal) {
            $parcelas[0]['amount'] -= round($valorTotalRecalculado - $valorTotal, 2);
        }

        return $parcelas;
    }
}
