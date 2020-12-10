<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SaleService;
use Illuminate\Http\JsonResponse;

class SalesController extends Controller
{
  /**
   *
   * @var SaleService
   */
  private $saleService;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(SaleService $saleService)
  {
    $this->saleService = $saleService;
  }

  public function uploadSales(Request $request): JsonResponse
  {
    return $this->saleService->uploadSales($request);
  }
}
