<?php

namespace App\Http\Services;

class FormatadorService
{
    /**
     * Converte uma string de data de 8 dígitos (formato YYYYMMDD)  em uma data
     * no formato YYYY-MM-DD, mas retorna false se a string não for formada
     * por exatamente 8 dígitos, ou se ela representar uma data inexistente.
     * 
     * @param string $data data no formato YYYYMMDD
     * @return bool|string
     */
    public function formataData(string $data)
    {
        if (!preg_match('/^(\d{4})(\d{2})(\d{2})$/', $data, $partesDaData)) {
            return false;
        }

        $ano = $partesDaData[1];
        $mes = $partesDaData[2];
        $dia = $partesDaData[3];

        if (!checkdate($mes, $dia, $ano)) {
            return false;
        }
        
        return "$ano-$mes-$dia";
    }

    /**
     * Converte uma string formada por 10 dígitos em sua representação
     * de ponto flutuante, considerando os 2 últimos dígitos como sendo os
     * dígitos que devem vir depois da vírgula.
     * 
     * @param string $valorReal dígitos que representam o valor float
     * @return float
     */
    public function formataValorReal(string $valorReal): float
    {
        preg_match('/^(\d{8})(\d{2})$/', $valorReal, $partesDoValorTotal);
        
        $parteInteira = (int)$partesDoValorTotal[1];
        $parteDecimal = $partesDoValorTotal[2];
        
        return (float)($parteInteira . '.' . $parteDecimal);
    }
}
