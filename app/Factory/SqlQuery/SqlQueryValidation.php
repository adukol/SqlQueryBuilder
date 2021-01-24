<?php

namespace App\Factory\SqlQuery;


use App\Factory\SqlQuery\SqlQueryFactory;


class SqlQueryValidation extends SqlQueryFactory{

    public static function validate($request){

        $command = $request->get('command');
        $query_data = $request->get('query_data');
        $table_count = sizeof($request->get('query_data')['table']);

        if(!$command || !$table_count || $query_data === [] ){
            return array(['status'=>'error','message'=> 'Invalid Request Details']);
        }

        $command_list = array("create","read","update","delete");

        if(!in_array($command,$command_list)){
            return array(['status'=>'error','message'=> 'Invalid Command Name']);
        }
        
        $sqlQuery = SqlQueryFactory::$command($query_data)->returnQuery();

        return $sqlQuery;
    }

}