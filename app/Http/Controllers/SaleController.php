<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use Illuminate\Http\JsonResponse;

use App\Services\Sales\SaleService;

class SaleController extends Controller
{
    private $service;

    public function __construct(SaleService $saleService)
    {
        $this->service = $saleService;
    }

    public function store(SaleRequest $request): JsonResponse
    {
        try {
            return $this->service->read(
                file($request->file('sales'))
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'message' => 'unexpected internal error.'
                ],
                500
            );
        }
    }
}
