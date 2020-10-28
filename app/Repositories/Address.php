<?php

namespace App\Repositories;

class Address extends HttpRequest
{
    public function getByCep(string $url): object
    {
        return $this->get($url);
    }
}
