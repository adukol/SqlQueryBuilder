<?php

namespace App\Http\Controllers;

use App\BuilderPattern\SqlQueryRead\MainQuery\MainQueryValidator;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\DB;

class SqlQueryReadController extends Controller
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

    public function tokenValidation($bearerToken)
     {
         //check if the bearer token is valid
        try {
            $token = $bearerToken; 
            $client = new \GuzzleHttp\Client(); 
            $response = $client->request('POST', 'http://52.77.208.128:3000/oauth/checkToken', [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                ],
            ]); 
            
            //get json response
            $json_response = json_decode($response->getBody()->getContents(), true);

            //if valid return the code. code: 1
            return $json_response['code'];

        } catch (\GuzzleHttp\Exception\BadResponseException $exception) {

            //get json response
            $json_response = json_decode($exception->getResponse()->getBody()->getContents(), true);

            //if valid return the code. code: 500 or 401
            return $json_response['code'];
        }
     }

    public function createSqlQueryRead(Request $request){

        //Extract Token from header then pass it to function validateToken() for validation of request
        $tokenValidation = $this->tokenValidation($request->bearerToken());  

        if($tokenValidation == 1){

            if(!$request->all()){

                $error_msg = array('status'=>'error','message'=> 'Invalid or Empty Request Details');
                return response()->json($error_msg,422);

            } else{
                // json_encode($request);
                // $res = SqlQueryReadValidator::validate($request->json());
                $res = MainQueryValidator::validate($request->all());

                // return $res;
                // switch through code for the response
                $res_data = [];
                switch($res['code']){
                    case 201:

                        $res_data = ['status'=>$res['status'],'query'=>$res['query'],'data'=>$res['data']];
                        
                        break;
                    case 500:

                        $res_data = ['status'=>$res['status'],'query'=>$res['query'],'data'=>$res['data']];
                        
                        break;
                    default:
                        
                        $res_data = ['status'=>$res['status'],'message'=>$res['message']];
                        
                        break;
                }

                return response()->json($res_data,$res['code']);
            }
        } else{

            $error_msg = ['status'=>'error','message'=> 'Unauthorized Request'];
            return response()->json($error_msg,401);
        }

    }

    
}
