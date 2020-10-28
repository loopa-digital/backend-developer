<?php

namespace App\Services\Sales;

use App\Repositories\Address;

class BaseSaleService
{
    private $address;
    protected $line;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    protected function getId()
    {
        return substr($this->line, 0, 3);
    }

    protected function getDate()
    {
        return date('Y-m-d', strtotime(substr($this->line, 3, 8)));
    }

    protected function getAmount()
    {
        return intval(substr($this->line, 11, 10)) / 100;
    }

    protected function getName()
    {
        return trim(substr($this->line, 23, 20));
    }

    protected function getCep()
    {
        return substr($this->line, 43, 8);
    }

    protected function getAddress()
    {
        $response = $this->address->getByCep(
            "https://viacep.com.br/ws/{$this->getCep()}/json"
        );

        if ($response->getStatusCode() === 200) {

            $contents = json_decode($response->getBody()->getContents());

            return [
                'street' => $contents->logradouro,
                'neighborhood' => $contents->bairro,
                'city' => $contents->localidade,
                'state' => $contents->uf,
                'postal_code' => $contents->cep
            ];
        } else {
            return [];
        }
    }

    protected function getInstallments()
    {
        return intval(substr($this->line, 21, 2));
    }

    protected function getInstallmentsInfo()
    {
        $infos = array();

        $amount = $this->getAmount();
        $installment = $this->getInstallments();

        $date = $this->getDate();

        $diff = ($amount % $installment) / 100;
        $amountInstallments = (float) bcdiv($amount, $installment, 2);

        for ($i = 0; $i < $installment; $i++) {

            $dueDate = 30 * ($i + 1);

            array_push($infos, [
                'installment' => $i + 1,
                'amount' => ($i == 0) ?
                    $amountInstallments + $diff : $amountInstallments,
                'date' => date(
                    'Y-m-d',
                    strtotime($date . " + {$dueDate} days")
                )
            ]);
        }

        return $infos;
    }
}
