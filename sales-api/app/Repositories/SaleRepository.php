<?php

namespace App\Repositories;

use App\Models\Sale;
use App\Interfaces\SaleInterface;

class SaleRepository implements SaleInterface
{
  private $sale;

  /**
   * Create a new repository instance.
   *
   * @return void
   */
  public function __construct(Sale $sale)
  {
    $this->sale = $sale;
  }

  /**
   * Set data to Model.
   *
   * @param string $id
   * @return App\Models\Sale
   */
  public function setData(array $data) : void
  {
    $amountConverted = convertToDouble($data['amount']);
    $date = convertToDate($data['date']);

    $this->sale->setCode($data['code']);
    $this->sale->setDate($date);
    $this->sale->setAmount($amountConverted);
  }

  public function getFields() : ?array
  {
    $model = [
      'code' => $this->sale->getCode(),
      'date' => $this->sale->getDate(),
      'amount' => $this->sale->getAmount()
    ];

    return $model;
  }
}