<?php

namespace App\Services\Sales;

use Illuminate\Http\JsonResponse;

class SaleService extends BaseSaleService
{

    public function read(array $content): JsonResponse
    {
        if (count($content) >= 1) {

            $sales = array();

            foreach ($content as $line) {

                $this->line = $line;

                $sale = array(
                    'id'       => $this->getId(),
                    'date'     => $this->getDate(),
                    'amount'   => $this->getAmount(),
                    'customer' => [
                        'name'    => $this->getName(),
                        'address' => $this->getAddress()
                    ],
                    'installments' => $this->getInstallmentsInfo()
                );

                array_push($sales, $sale);
            }

            return response()->json(
                [
                    'message' => 'sales successfully listed.',
                    'sales'   => $sales
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'message' => 'sales not found.',
                    'sales'   => []
                ],
                404
            );
        }
    }
}
