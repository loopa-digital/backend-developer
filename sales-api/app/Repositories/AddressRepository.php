<?php

namespace App\Repositories;

use GuzzleHttp\Client;
use App\Models\Address;
use App\Interfaces\AddressInterface;
class AddressRepository implements AddressInterface
{
  private $address;
  private $client;

  /**
   * Create a new repository instance.
   *
   * @return void
   */
  public function __construct(Address $address)
  {
    $this->address = $address;
    $this->client = new Client(['base_uri' => 'https://viacep.com.br/']);
  }

  public function setData(array $data) : void
  {
    $response = $this->client->request('GET', "ws/{$data['postalCode']}/json");
    $json = json_decode($response->getBody());

    $this->address->setStreet($json->logradouro);
    $this->address->setNeighborhood($json->bairro);
    $this->address->setCity($json->localidade);
    $this->address->setState($json->uf);
    $this->address->setPostalCode($json->cep);
  }

  public function getFields() : ?array
  {
    $address = [
      'street' => $this->address->getStreet(),
      'neighborhood' => $this->address->getNeighborhood(),
      'city' => $this->address->getCity(),
      'state' => $this->address->getState(),
      'postal_code' => $this->address->getPostalCode()
    ];

    return $address;
  }
}