<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Interfaces\CustomerInterface;

class CustomerRepository implements CustomerInterface
{
  private $customer;

  /**
   * Create a new repository instance.
   *
   * @return void
   */
  public function __construct(Customer $customer)
  {
    $this->customer = $customer;
  }

  /**
   * Set data to Model.
   *
   * @param string $id
   * @return App\Models\Sale
   */
  public function setData(array $data) : void
  {
    $this->customer->setName($data['customerName']);
  }

  public function getFields() : ?array
  {
    $customer = [
      'name' => $this->customer->getName()
    ];

    return $customer;
  }
}