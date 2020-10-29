<?php

namespace App\Repositories;

use GuzzleHttp\Client;

use GuzzleHttp\Exception\BadResponseException;

class HttpRequest
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function get(string $url): object
    {
        try {
            return $this->client->request('GET', $url);
        } catch (BadResponseException $e) {
            return $e->getResponse();
        }
    }
}
