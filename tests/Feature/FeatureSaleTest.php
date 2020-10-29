<?php

use Illuminate\Http\UploadedFile;

class FeatureSaleTest extends TestCase
{
    /** @test */

    public function invalid_file_upload()
    {
        $response = $this->post(
            '/v1/sales',
            [
                'sales' => UploadedFile::fake()->create('sales.pdf')
            ]
        );

        $response->assertResponseStatus(422);
    }

    /** @test */

    public function error_reading_an_entire_file_and_converting_it_to_an_array()
    {
        $response = $this->post(
            '/v1/sales',
            [
                'sales' => UploadedFile::fake()->create('sales.txt')
            ]
        );

        $response->assertResponseStatus(500);
    }
}
