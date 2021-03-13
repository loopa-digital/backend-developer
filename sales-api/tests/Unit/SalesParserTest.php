<?php

use App\Libraries\InvalidContentException;
use App\Libraries\SalesParser;

class SalesParserTest extends TestCase
{
    public function test_it_parses_a_string_into_a_collection()
    {
        $content = "12320201012000011132703Comprador 1         06050190
32120201013000015637504Comprador 2         06330000
23120201014000026370003Comprador 3         01454000
";

        $sales = (new SalesParser)->parse($content);

        $this->assertInstanceOf(Illuminate\Support\Collection::class, $sales);
        $this->assertArrayHasKey('sales', $sales);
        $this->assertCount(3, $sales->first());
    }

    public function test_it_throws_an_exception_when_the_string_is_invalid()
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage('Content structure is invalid - Line length is incorrect');

        $content = "12320201012000011132703Comprador 1         06050190
32120201013000015637504Comprador 2         06330000
23120201014000026370003Compr 3         01454000
";
        $sales = (new SalesParser)->parse($content);
    }

    public function test_it_throws_an_exception_when_the_string_is_empty()
    {
        $this->expectException(InvalidContentException::class);
        $this->expectExceptionMessage('Content is empty');

        $content = "";

        $sales = (new SalesParser)->parse($content);
    }
}
