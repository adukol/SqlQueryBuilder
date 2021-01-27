<?php

namespace App\Component\SqlQuery;

use App\Component\SqlQuery\SqlQueryBuilder;

class SqlQueryValidator extends SqlQueryBuilder{

    public static function validate($request){

        $command = $request->get('command');
        
        if(!$command ){
            return ['status'=>'error','code'=>'400', 'message'=> 'Missing Command Name'];
            // throw new Exception('Missing Command Name',400);
        }

        $query_data = $request->get('query_data');

        if($query_data === [] ){
            return ['status'=>'error','code'=>'400', 'message'=> 'Missing Query Data'];
        }

        $table_count = sizeof($request->get('query_data')['table']);
        
        if(!$table_count){
            return ['status'=>'error', 'code'=>'400', 'message'=> 'Invalid Number of Tables'];
        }

        $command_list = ["create","read","update","delete"];

        if(!in_array($command,$command_list)){
            return ['status'=>'error', 'code'=>'400', 'message'=> 'Invalid Command Name'];
        }
        
        $sqlQuery = SqlQueryBuilder::$command($query_data)->getQuery();

        return $sqlQuery;
    }

}