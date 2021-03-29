<?php

namespace App\Http\Controllers;

use App\Libraries\InvalidContentException;
use App\Libraries\SalesParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SaleController extends Controller
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

    public function __invoke(Request $request)
    {
        $attrs = $this->validate($request, [
            'file' => 'required|mimes:txt'
        ]);

        $file = $attrs['file'];
        $content = $file->get();

        try {
            $sales = (new SalesParser)->parse($content);
        } catch (InvalidContentException $e) {
            return new JsonResponse([
                'code' => 422,
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return new JsonResponse([
                'code' => 500,
                'error' => $e->getMessage()
            ], 500);
        }

        return $sales;
    }
}
