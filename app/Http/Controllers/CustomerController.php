<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\Helper;
class CustomerController extends Controller 
{   
    public function interpreter(Request $request){
          
        $this->validate($request, [
            'customerTxt' => 'required|mimes:txt',
        ]);

        //Get file customer
        $fileCustomer = $request->file('customerTxt'); 
        $fileExtension = $fileCustomer->clientExtension(); 
        
        //Validate extension file
        if($fileExtension != "txt"){
            return response()->json([
                'customerTxt' => 'The customer txt must be a file of type: txt.'
            ], 400);
        }   

        $customerContent = explode("\n", $fileCustomer->get());

        //File validate content
        $Nocustomer = true;
        $compradores = array();
        foreach($customerContent as $item){
            if(strlen($item) >= 52){
               $compradores[] = $item;
               $Nocustomer = false;
            }
        }

        if($Nocustomer){
            return response()->json([
                'error' => 'No customer data, please check the file'
            ], 400);
        }

        $interpreterResponse = array();
        foreach($compradores as $customer){
            //Get information file 
            $customerid = substr($customer, 0, 3);
            $saleDate = substr($customer, 3, 8);
            $saleValue = substr($customer, 11, 10);
            $installmentNumber = substr($customer, 21, 2);
            $customerName = substr($customer, 23, 20);
            $customerCep = substr($customer, 43, 8);
         
            //Information transformation
            $saleDate = date("Y-m-d", strtotime($saleDate));
            if(!is_numeric($saleValue)){
                return response()->json([
                    'error' => 'Sale value wrong!'
                ], 400);
            }

            $saleValue = number_format((float)($saleValue/100), 2, '.', '');
            $installmentNumber = intval($installmentNumber);
            $customerName = trim($customerName);
            $cepData = Helper::getAddress($customerCep);

            if(!$cepData){
                return response()->json([
                    'error' => 'CEP wrong!'
                ], 400);
            }
            
            //Make installments
            if($installmentNumber){
                $installments = array();
                $installmentsDate = $saleDate;
                $installmentAmount = (float)number_format((float)($saleValue/$installmentNumber), 2, '.', ''); 

                //Verify Diff intallment to total value
                $installmentDiff = $saleValue - ($installmentAmount*$installmentNumber);
                $installmentAmountDiff = 0;

                for ($i=1; $i <= $installmentNumber ; $i++) {  

                    //Add diff to first installment 
                    if($i == 1){
                        if($installmentDiff){
                            $installmentAmountDiff = $installmentAmount + $installmentDiff;
                        }
                    }

                    $installmentsDate = date('Y-m-d', strtotime("+30 days",strtotime($installmentsDate))); 
                    $diasemana_numero = date('w', strtotime($installmentsDate));    
                    
                    //Verify weekend
                    if(($diasemana_numero == 0) || ($diasemana_numero == 6)){
                        $installmentsDate = date('Y-m-d', strtotime("next monday",strtotime($installmentsDate))); 
                    }
                    
                    //Installment array
                    $installments[] = array(
                        "installment" => $i,
                        "amount" => (($i == 1) && ($installmentAmountDiff))? $installmentAmountDiff : (float)$installmentAmount, 
                        "date" =>$installmentsDate
                    );
                }
            }

            //Set customer response
            $customerInformations = array(
                "id" => $customerid,
                "date" => $saleDate,
                "amount" => $saleValue,
                "customer" => array(
                    "name" => $customerName,
                    "address" => array(
                        "street" => $cepData["logradouro"],
                        "neighborhood" =>  $cepData["bairro"],
                        "city" =>  $cepData["localidade"],
                        "state" =>  $cepData["uf"],
                        "postal_code" =>  $cepData["cep"]
                    )
                ),
                "installments" => $installments
            );

            $interpreterResponse[] = $customerInformations;
        }
    
        return response()->json((["sales" => $interpreterResponse]), 200);
    }
    
}
