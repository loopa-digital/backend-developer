<?php

namespace App\Http\Controllers;

use App\Services\Extractor;
use App\Services\Interpreter;
use App\Services\SalesDataService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ProcessFileController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'sales_file' => 'required|file|mimes:txt'
        ]);

        $salesDataService = new SalesDataService($request->sales_file, new Client());
        $salesData = $salesDataService->run();

        return response()->json([
            'sales' => $salesData
        ], 200);
    }
}
