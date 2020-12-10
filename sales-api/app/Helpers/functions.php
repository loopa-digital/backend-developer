<?php

use Carbon\Carbon;

function extractTextInfo(string $line)
{
  $characters = str_split($line);

  if (count($characters)) {

    $data = [
      'code' => '',
      'date' => '',
      'amount' => '',
      'installments' => '',
      'customerName' => '',
      'postalCode' => ''
    ];

    foreach ($characters as $key => $character) {
      if ($key < 3) {
        $data['code'] .= $character;
      }

      if ($key >= 3 && $key < 11) {
        $data['date'] .= $character;
      }

      if ($key >= 11 && $key < 21) {
        $data['amount'] .= $character;
      }

      if ($key >= 21 && $key < 23) {
        $data['installments'] .= $character;
      }

      if ($key >= 23 && $key < 43) {
        $data['customerName'] .= $character;
      }

      if ($key >= 43 && $key < 51) {
        $data['postalCode'] .= $character;
      }
    }

    return $data;
  }
}

function convertToDouble(string $value)
{
  $cents = intval(substr($value, (strlen($value) - 2), 2)) / 100;
  $amount = intval(substr($value, 0, (strlen($value) - 2)));
  $amountWithCents = round($amount + $cents, 2);
  return $amountWithCents;
}

function formatNumber($number) {
  return number_format($number, 2);
}

function convertToDate(string $date)
{
  $year = substr($date, 0, 4);
  $month = substr($date, 4, 2);
  $day = substr($date, 6, 2);

  return Carbon::createFromDate($year, $month, $day)->toDateString();
}