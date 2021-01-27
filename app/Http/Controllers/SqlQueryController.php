<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\DB;


use App\Component\SqlQuery\SqlQueryValidator;


class SqlQueryController extends Controller
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

    // public function getAllTable(){
    //     $dbName = env('DB_DATABASE');
    //     $tables = DB::select("SELECT table_name AS tableName FROM information_schema.tables WHERE table_type = 'base table' AND table_schema = '$dbName'");
    //     return response()->json($tables,200);
    // }

    // public function getAllColumn($tableName){
    //     $columns = DB::select("SELECT COLUMN_NAME AS columnName FROM information_schema.COLUMNS WHERE TABLE_NAME = '$tableName'");
    //     return response()->json($columns,200);
    // }

    public function createSqlQuery(Request $request){

        if(!$request->all()){

            $error_msg = array('status'=>'error','message'=> 'Invalid or Empty Request Details');
            return response()->json($error_msg,422);

        } else{
            // json_encode($request);
            $res = SqlQueryValidator::validate($request->json());

            // return $res;
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

    }

    
}
