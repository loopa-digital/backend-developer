<?php

namespace App\Interfaces;

interface AbstractInterface
{
  public function setData(array $data) : void;
  public function getFields() : ?array;
}