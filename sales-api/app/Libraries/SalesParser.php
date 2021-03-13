<?php

namespace App\Libraries;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SalesParser
{
    /**
     * @param string $content
     *
     * @return Collection
     */
    public function parse($content)
    {
        $sales = collect();

        $this->checkEmpty($content);

        $lines = Str::of($content)->trim()->explode("\n")->filter();

        foreach ($lines as $line) {
            $this->checkLine($line);

            $line = Str::of($line);

            $id = $line->substr(0, 3);
            $date = Carbon::parse($line->substr(3, 8));
            $amount = floatval($line->substr(11, 8)->append('.') . $line->substr(19, 2));
            $installments = (string) $line->substr(21, 2);
            $customer = $line->substr(23, 20)->trim();
            $cep = $line->substr(43, 8);

            $addressInfo = $this->getAddressInfo($cep);

            $installmentsInfo = $this->getInstallments($amount, $installments, $date);

            $sales->push([
                'id' => $id,
                'date' => $date->format('Y-m-d'),
                'amount' => $amount,
                'customer' => [
                    'name' => $customer,
                    'address' => $addressInfo
                ],
                'installments' => [
                    ...$installmentsInfo
                ]
            ]);
        }

        return collect(['sales' => $sales]);
    }

    protected function getAddressInfo($cep)
    {
        $address = json_decode(
            file_get_contents("https://viacep.com.br/ws/{$cep}/json/")
        );

        return [
            'street' => $address->logradouro ?? 'N/A',
            'neighborhood' => $address->bairro ?? 'N/A',
            'city' => $address->localidade ?? 'N/A',
            'state' => $address->uf ?? 'N/A',
            'postal_code' => $address->cep ?? $cep
        ];
    }

    protected function getInstallments($amount, $installments, $startDate)
    {
        $installmentsInfo = collect();

        $installmentAmount = floatval(
            number_format($amount / intval($installments), 2, '.', '')
        );

        foreach(range(1, intval($installments)) as $installment) {
            $installmentDate = $startDate->copy()->addMonth($installment);

            while ($installmentDate->isWeekend()) {
                $installmentDate->addDay();
            }

            $installmentsInfo->push([
                'installment' => $installment,
                'amount' => $installmentAmount,
                'date' => $installmentDate->format('Y-m-d')
            ]);
        }

        $amountDiff = $amount - $installmentsInfo->sum(fn($i) => $i['amount']);

        if ($amountDiff > 0) {
            $firstInstallment = $installmentsInfo->first();

            $firstInstallment['amount'] += $amountDiff;

            $installmentsInfo[0] = $firstInstallment;
        }

        return $installmentsInfo;
    }

    protected function checkEmpty($content)
    {
        if (Str::of($content)->trim()->length() === 0) {
            throw new InvalidContentException('Content is empty');
        }
    }

    protected function checkLine($line)
    {
        if (Str::of($line)->trim()->length() !== 51 ) {
            throw new InvalidContentException('Content structure is invalid - Line length is incorrect');
        }
    }
}
