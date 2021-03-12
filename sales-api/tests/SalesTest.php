<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class SalesTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_receives_a_txt_file_and_returns_json()
    {
        $file = UploadedFile::fake()->createWithContent('sales.txt',
            "12320201012000011132703Comprador 1         06050190
32120201013000015637504Comprador 2         06330000
23120201014000026370003Comprador 3         01454000
");

        $response = $this->post('/sales', [
            'file' => $file
        ]);

        $response->assertResponseStatus(200);

        $response->seeJson();
    }
}
