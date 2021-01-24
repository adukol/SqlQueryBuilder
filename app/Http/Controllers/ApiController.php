<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\DB;


use App\Factory\SqlQuery\SqlQueryValidation;


class ApiController extends Controller
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

    public function getAllTable(){
        $dbName = env('DB_DATABASE');
        $tables = DB::select("SELECT table_name AS tableName FROM information_schema.tables WHERE table_type = 'base table' AND table_schema = '$dbName'");
        return response()->json($tables,200);
    }

    public function getAllColumn($tableName){
        $columns = DB::select("SELECT COLUMN_NAME AS columnName FROM information_schema.COLUMNS WHERE TABLE_NAME = '$tableName'");
        return response()->json($columns,200);
    }

    public function createSqlQuery(Request $request){
        // return response()->json(["function"=>"create select"]);
        
        $data = SqlQueryValidation::validate($request->json());
        return response()->json($data,201);
    }

    
}
