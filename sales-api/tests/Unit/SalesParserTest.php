<?php

use App\Libraries\SalesParser;

class SalesParserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
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
}
