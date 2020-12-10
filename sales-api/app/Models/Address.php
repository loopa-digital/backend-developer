<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
  /**
   * @var string
   */
  private $street;

  /**
   * @var string
   */
  private $neighborhood;

  /**
   * @var string
   */
  private $city;

  /**
   * @var string
   */
  private $state;

  /**
   * @var string
   */
  private $postal_code;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'street',
    'neighborhood',
    'city',
    'state',
    'postal_code'
  ];

  public function getStreet() : string
  {
    return $this->street;
  }

  public function setStreet(string $street) : void
  {
    $this->street = $street;
  }

  public function getNeighborhood() : string
  {
    return $this->neighborhood;
  }

  public function setNeighborhood(string $neighborhood) : void
  {
    $this->neighborhood = $neighborhood;
  }

  public function getCity() : string
  {
    return $this->city;
  }

  public function setCity(string $city) : void
  {
    $this->city = $city;
  }

  public function getState() : string
  {
    return $this->state;
  }

  public function setState(string $state) : void
  {
    $this->state = $state;
  }

  public function getPostalCode() : string
  {
    return $this->postal_code;
  }

  public function setPostalCode(string $postal_code) : void
  {
    $this->postal_code = $postal_code;
  }
}