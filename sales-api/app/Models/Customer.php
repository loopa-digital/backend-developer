<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
  /**
   * @var string
   */
  private $name;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name'
  ];

  public function getName() : string
  {
    return $this->name;
  }

  public function setName(string $name) : void
  {
    $this->name = $name;
  }
}