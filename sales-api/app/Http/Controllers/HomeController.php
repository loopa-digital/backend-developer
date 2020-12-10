<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Index page with API Description
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $apiDescription = [
      'Name' => 'Loopa Upload Sales API',
      'Developed By' => 'Rafael Gomes',
      'GitHub Repository' => 'https://github.com/rafaelfgomes/backend-developer/tree/feature/develop-sales-api/sales-api'
    ];

    return response()->json(['Api Description' => $apiDescription]);
  }
}
