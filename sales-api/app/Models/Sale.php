<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
  /**
   * @var int
   */
  private $code;

  /**
   * @var string
   */
  private $date;

  /**
   * @var float
   */
  private $amount;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'code',
    'date',
    'amount'
  ];

  public function getCode() : int
  {
    return $this->code;
  }

  public function setCode(int $code) : void
  {
    $this->code = $code;
  }

  public function getDate() : string
  {
    return $this->date;
  }

  public function setDate(string $date) : void
  {
    $this->date = $date;
  }

  public function getAmount() : float
  {
    return $this->amount;
  }

  public function setAmount(float $amount) : void
  {
    $this->amount = $amount;
  }
}