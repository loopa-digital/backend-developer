<?php

namespace App\Models;

use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
  /**
   * @var int
   */
  private $number;

  /**
   * @var float
   */
  private $amount;

  /**
   * @var Date
   */
  private $date;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'number',
    'amount',
    'date'
  ];

  public function getNumber() : int
  {
    return $this->number;
  }

  public function setNumber(string $number) : void
  {
    $this->number = $number;
  }

  public function getAmount() : float
  {
    return $this->amount;
  }

  public function setAmount(float $amount) : void
  {
    $this->amount = $amount;
  }

  public function getDate() : Date
  {
    return $this->date;
  }

  public function setDate(Date $date) : void
  {
    $this->date = $date;
  }
}