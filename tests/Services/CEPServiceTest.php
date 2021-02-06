<?php

use App\Http\Services\CEPService;

class CEPServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider cepsNoFormatoIncorreto
     */
    public function naoConsegueEncontrarCEPNoFormatoIncorreto(string $cep): void
    {
        $classeCEPService = new \ReflectionClass('App\Http\Services\CEPService');
        $metodoBuscaCEP = $classeCEPService->getMethod('buscaCEP');
        $metodoBuscaCEP->setAccessible(true);
        
        $cepService = new CEPService();

        $resultado = $metodoBuscaCEP->invokeArgs($cepService, [$cep]);

        $this->assertFalse($resultado);
    }

    /**
     * @test
     * @dataProvider cepsNoFormatoIncorreto
     */
    public function naoConsegueProcessarCEPNoFormatoIncorretoENemInterpretarEndereco(string $cep): void
    {
        $classeCEPService = new \ReflectionClass('App\Http\Services\CEPService');
        $metodoProcessaCEPEInterpretaEndereco = $classeCEPService->getMethod('processaCEPEInterpretaEndereco');
        $metodoProcessaCEPEInterpretaEndereco->setAccessible(true);
        
        $cepService = new CEPService();

        $resultado = $metodoProcessaCEPEInterpretaEndereco->invokeArgs($cepService, [$cep]);

        $this->assertFalse($resultado);
    }

    public function cepsNoFormatoIncorreto(): array
    {
        return [
            ['123'],
            ['abc'],
            ['054010']
        ];
    }
}
