<?php

namespace App\BuilderPattern\SqlQueryRead\MainQuery;

use App\BuilderPattern\SqlQueryRead\Director;
use App\BuilderPattern\SqlQueryRead\MainQuery\MainQuery;
use App\BuilderPattern\SqlQueryRead\MainQuery\MainQueryBuilder as MainQueryBuilder;

class MainQueryValidator extends MainQuery{

    public static function validate($request){

        //check if key 'command' exist. if true pass the value to $command else pass null
        $command = 
            array_key_exists('command',$request) && is_string($request['command']) && $request['command'] == "read"? 
            $request['command'] : null;
        
        //check if $command is null
        if(!$command){
            return ['status'=>'error','code'=>'400', 'message'=> 'Missing or Invalid Command Name'];
        } 

        //check if key 'query_data' exist and also an array. if true pass the value to $query_data else pass null
        $query_data = 
            array_key_exists('query_data',$request) && is_array($request['query_data'])? 
            $request['query_data'] : null;

        //check if $query_data is null
        if(!$query_data){
            return ['status'=>'error','code'=>'400', 'message'=> 'Missing or Invalid Query Data'];
        }

        //check if key 'select' exist and also an array. if true pass the value to $query_data else pass null
        $select = 
            array_key_exists('select',$query_data) && is_array($query_data['select']) && sizeof($query_data['select']) != 0? 
            $query_data['select'] : null;

        //check if $select is null
        if(!$select){
            return ['status'=>'error','code'=>'400', 'message'=> 'Missing or Invalid Select Details'];
        } 

        //check if key 'join' exist and if it is an array. if true then pass the value to $join 
        $join = 
            array_key_exists('join',$query_data) && is_array($query_data['join'])? 
            $query_data['join']: "";

        //check if key 'where' exist and if it is an array. if true then pass the value to $join 
        $where =    
            array_key_exists('where',$query_data) && is_array($query_data['where'])? 
            $query_data['where']: "";

        //check if key 'group_by' exist and if it is an array. if true then pass the value to $join 
        $group_by = 
            array_key_exists('group_by',$query_data) && is_array($query_data['group_by'])? 
            $query_data['group_by']: "";

        //check if key 'having' exist and if it is an array. if true then pass the value to $join 
        $having = 
            array_key_exists('having',$query_data) && is_array($query_data['having'])? 
            $query_data['having']: "";

        //check if key 'order_by' exist and if it is an array. if true then pass the value to $join 
        $order_by = 
            array_key_exists('order_by',$query_data) && is_array($query_data['order_by'])? 
            $query_data['order_by']: "";

        //check if key 'limit_offset' exist and if it is an array. if true then pass the value to $join 
        $limit_offset = 
            array_key_exists('limit_offset',$query_data) && is_array($query_data['limit_offset'])? $query_data['limit_offset']: "";

        /*****builder pattern creation*******/
        $mainQueryBuilder = new MainQueryBuilder($select, $join, $where, $group_by, $having, $order_by, $limit_offset);
        
        $director = new Director();
        
        $director->build($mainQueryBuilder);

        $response_data = $mainQueryBuilder->getQuery()->getDataFromQuery();

        return $response_data;
    }
}

/*****factory pattern creation*******/
// $sqlQuery = SqlQueryBuilder::$command($query_data)->getQuery();

// return $sqlQuery;