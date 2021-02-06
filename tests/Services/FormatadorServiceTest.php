<?php

use App\Http\Services\FormatadorService;

class FormatadorServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider datasNoFormatoEOuValorIncorreto
     */
    public function naoConsegueFormataDataSeFormatoOuValorEstiverIncorreto(
        string $ano,
        string $mes,
        string $dia
    ): void {
        $formatadorService = new FormatadorService();

        $resultado = $formatadorService->formataData($ano . $mes . $dia);

        $this->assertFalse($resultado);
    }

    public function datasNoFormatoEOuValorIncorreto(): array
    {
        return [
            ['2021', '1', '11'],
            ['202', '11', '11'],
            ['209', '0', '0'],
            ['2020', '12', '45']
        ];
    }

    /**
     * @test
     * @dataProvider datasNoFormatoEValorCorreto
     */
    public function consegueFormatarDataSeVierNoFormatoEValorCorreto(
        string $ano,
        string $mes,
        string $dia
    ): void {
        $formatadorService = new FormatadorService();

        $resultado = $formatadorService->formataData($ano . $mes . $dia);

        $this->assertEquals($ano . '-' . $mes . '-' . $dia, $resultado);
    }

    public function datasNoFormatoEValorCorreto(): array
    {
        return [
            ['2021', '12', '11'],
            ['2021', '11', '11'],
            ['2019', '01', '01'],
            ['2020', '02', '29']
        ];
    }
}
