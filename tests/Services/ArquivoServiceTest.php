<?php

use App\Http\Services\ArquivoService;
use App\Http\Services\CEPService;
use App\Http\Services\FormatadorService;
use App\Http\Services\ParcelasService;

class ArquivoServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider linhasNoFormatoIncorreto
     */
    public function naoConsegueDecomporLinhaNoFormatoIncorreto(string $linha): void
    {
        $classeArquivoService = new \ReflectionClass('App\Http\Services\ArquivoService');
        $metodoDecompoeLinha = $classeArquivoService->getMethod('decompoeLinha');
        $metodoDecompoeLinha->setAccessible(true);

        $formatadorService = new FormatadorService();
        $cepService = new CEPService();
        $parcelasService = new ParcelasService();
        
        $arquivoService = new ArquivoService(
            $formatadorService,
            $cepService,
            $parcelasService
        );

        $resultado = $metodoDecompoeLinha->invokeArgs($arquivoService, [$linha]);

        $this->assertFalse($resultado);
    }

    public function linhasNoFormatoIncorreto()
    {
        return [
            [base64_encode('abc')],
            [base64_encode('23120201014000026370003Comprador 3         01454000 1')],
            [base64_encode('12320201012000011132703Comprador 1        06050190')],
            [base64_encode('12320201012000011132703Comprador 1       06050190')],
            [base64_encode('12320201012000011132703Comprador 1      06050190')],
            [base64_encode('12320201012000011132703Comprador 1     06050190')],
            [base64_encode('12320201012000011132703Comprador 1    06050190')],
            [base64_encode('12320201012000011132703Comprador 1   06050190')],
            [base64_encode('12320201012000011132703Comprador 1  06050190')],
            [base64_encode('12320201012000011132703Comprador 106050190')]
        ];
    }

    /**
     * @test
     */
    public function consegueDecomporLinhaNoFormatoCorreto(): void
    {
        $classeArquivoService = new \ReflectionClass('App\Http\Services\ArquivoService');
        $metodoDecompoeLinha = $classeArquivoService->getMethod('decompoeLinha');
        $metodoDecompoeLinha->setAccessible(true);

        $formatadorService = new FormatadorService();
        $cepService = new CEPService();
        $parcelasService = new ParcelasService();
        
        $arquivoService = new ArquivoService(
            $formatadorService,
            $cepService,
            $parcelasService
        );

        $id = '231';
        $dataVenda = '20201014';
        $valorVenda = '0000263700';
        $numeroDeParcelas = '03';
        $nomeComprador = 'Comprador 3         ';
        $cepComprador = '01454000';

        $linha = "{$id}{$dataVenda}{$valorVenda}{$numeroDeParcelas}{$nomeComprador}{$cepComprador}";

        $resultado = $metodoDecompoeLinha->invokeArgs($arquivoService, [$linha]);

        $this->assertIsArray($resultado);
        $this->assertEquals(6, count($resultado));
        $this->assertEquals($id, $resultado[0]);
        $this->assertEquals($dataVenda, $resultado[1]);
        $this->assertEquals($valorVenda, $resultado[2]);
        $this->assertEquals($numeroDeParcelas, $resultado[3]);
        $this->assertEquals($nomeComprador, $resultado[4]);
        $this->assertEquals($cepComprador, $resultado[5]);
    }

    /**
     * @test
     */
    public function naoConsegueProcessarConteudoComLinhaNoFormatoIncorreto()
    {
        $formatadorService = new FormatadorService();
        $cepService = new CEPService();
        $parcelasService = new ParcelasService();
        
        $arquivoService = new ArquivoService(
            $formatadorService,
            $cepService,
            $parcelasService
        );

        $conteudo = <<<CONTEUDO
12320201012000011132703Comprador 1         06050190
32120201013000015637504Comprador 2         063300002
23120201014000026370003Comprador 3         014540003
CONTEUDO;

        $resultado = $arquivoService->processaConteudo($conteudo);

        $this->assertFalse($resultado);
    }

    /**
     * @test
     * @dataProvider conteudosNoFormatoCorreto
     */
    public function consegueProcessarConteudoComTodasAsLinhasNoFormatoCorreto(array $conteudo): void
    {
        $formatadorService = new FormatadorService();
        $cepService = new CEPService();
        $parcelasService = new ParcelasService();
        
        $arquivoService = new ArquivoService(
            $formatadorService,
            $cepService,
            $parcelasService
        );

        $conteudo = $conteudo[0];

        $conteudoComoTexto = '';
        $numeroDeLinhas = count($conteudo);
        $numeroDePartes = 7;

        for ($linha = 0; $linha < $numeroDeLinhas; ++$linha) {
            foreach ($conteudo[$linha] as $parte => $conteudoParte) {
                $conteudoComoTexto .= $conteudoParte;
            }

            if ($linha < $numeroDeLinhas - 1) {
                $conteudoComoTexto .= PHP_EOL;
            }
        }

        $resultado = $arquivoService->processaConteudo($conteudoComoTexto);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('sales', $resultado);
        $this->assertEquals($numeroDeLinhas, count($resultado['sales']));

        for ($linha = 0; $linha < $numeroDeLinhas; ++$linha) {
            $id = $conteudo[$linha]['id'];
            $dataVenda = $conteudo[$linha]['dataVenda'];
            $valorVendaParteInteira = $conteudo[$linha]['valorVendaParteInteira'];
            $valorVendaParteReal = $conteudo[$linha]['valorVendaParteReal'];
            $valorVenda = (int)$valorVendaParteInteira . ((int)$valorVendaParteReal > 0 ? '.' . (int)$valorVendaParteReal : '');

            $this->assertArrayHasKey('id', $resultado['sales'][$linha]);
            $this->assertEquals($id, $resultado['sales'][$linha]['id']);
            $this->assertArrayHasKey('date', $resultado['sales'][$linha]);
            $this->assertEquals($dataVenda, str_replace('-', '', $resultado['sales'][$linha]['date']));
            $this->assertArrayHasKey('amount', $resultado['sales'][$linha]);

            $valorObtidoEmPartes = explode('.', (string)$resultado['sales'][$linha]['amount']);
            $this->assertEquals(
                $valorVenda,
                $valorObtidoEmPartes[0] . (isset($valorObtidoEmPartes[1]) ? '.' . $valorObtidoEmPartes[1] : '')
            );

            $this->assertArrayHasKey('customer', $resultado['sales'][$linha]);
            $this->assertArrayHasKey('name', $resultado['sales'][$linha]['customer']);

            $nomeComprador = $conteudo[$linha]['nomeComprador'];

            $this->assertEquals(rtrim($nomeComprador), $resultado['sales'][$linha]['customer']['name']);
            $this->assertArrayHasKey('address', $resultado['sales'][$linha]['customer']);
            $this->assertArrayHasKey('street', $resultado['sales'][$linha]['customer']['address']);
            $this->assertArrayHasKey('neighborhood', $resultado['sales'][$linha]['customer']['address']);
            $this->assertArrayHasKey('city', $resultado['sales'][$linha]['customer']['address']);
            $this->assertArrayHasKey('state', $resultado['sales'][$linha]['customer']['address']);
            $this->assertArrayHasKey('postal_code', $resultado['sales'][$linha]['customer']['address']);

            preg_match('/^(\d{5})(\d{3})$/', $conteudo[$linha]['cepComprador'], $cepComprador);

            $this->assertEquals($cepComprador[1] . '-' . $cepComprador[2], $resultado['sales'][$linha]['customer']['address']['postal_code']);

            $numeroDeParcelas = (int)$conteudo[$linha]['numeroDeParcelas'];

            $this->assertArrayHasKey('installments', $resultado['sales'][$linha]);
            $this->assertEquals($numeroDeParcelas, count($resultado['sales'][$linha]['installments']));

            $somaValorParcelas = 0;

            for ($parcela = 0; $parcela < $numeroDeParcelas ; ++$parcela) {
                $somaValorParcelas += $resultado['sales'][$linha]['installments'][$parcela]['amount'];

                $this->assertEquals($parcela + 1, $resultado['sales'][$linha]['installments'][$parcela]['installment']);
            }

            $this->assertEquals((string)$valorVenda, (string)$somaValorParcelas);
        }
    }

    public function conteudosNoFormatoCorreto(): array
    {
        return [
            [
                [
                    [
                        [
                            'id' => '123',
                            'dataVenda' => '20201012',
                            'valorVendaParteInteira' => '00001113',
                            'valorVendaParteReal' => '27',
                            'numeroDeParcelas' => '03',
                            'nomeComprador' => 'Comprador 1         ',
                            'cepComprador' => '06050190'
                        ],
                        [
                            'id' => '231',
                            'dataVenda' => '20201014',
                            'valorVendaParteInteira' => '00002637',
                            'valorVendaParteReal' => '00',
                            'numeroDeParcelas' => '03',
                            'nomeComprador' => 'Comprador 3         ',
                            'cepComprador' => '01454000'
                        ]
                    ]
                ]
            ],
            [
                [
                    [
                        [
                            'id' => '321',
                            'dataVenda' => '20201013',
                            'valorVendaParteInteira' => '00001563',
                            'valorVendaParteReal' => '75',
                            'numeroDeParcelas' => '04',
                            'nomeComprador' => 'Comprador 2         ',
                            'cepComprador' => '06330000'
                        ]
                    ]
                ]
            ],
            [
                [
                    [
                        [
                            'id' => '453',
                            'dataVenda' => '20201019',
                            'valorVendaParteInteira' => '00001567',
                            'valorVendaParteReal' => '77',
                            'numeroDeParcelas' => '03',
                            'nomeComprador' => 'Comprador 4         ',
                            'cepComprador' => '06330000'
                        ]
                    ]
                ]
            ]
        ];
    }
}
