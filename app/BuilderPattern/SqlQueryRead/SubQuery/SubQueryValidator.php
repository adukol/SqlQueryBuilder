<?php

namespace App\BuilderPattern\SqlQueryRead\SubQuery;

use App\BuilderPattern\SqlQueryRead\Director;
use App\BuilderPattern\SqlQueryRead\SubQuery\SubQuery;
use App\BuilderPattern\SqlQueryRead\SubQuery\SubQueryBuilder;

class SubQueryValidator extends SubQuery{

    public static function validate($subQuery){
        
        //check if key 'select' exist and also an array. if true pass the value to $subQuery else pass null
        $subQuery_select = 
            array_key_exists('select',$subQuery) && is_array($subQuery['select']) && sizeof($subQuery['select']) != 0? 
            $subQuery['select'] : null;

        //check if $select is null
        if(!$subQuery_select){
            return ['status'=>'error','code'=>'400', 'message'=> 'Missing or Invalid Select Details'];
        } 

        //check if key 'join' exist and if it is an array. if true then pass the value to $join 
        $subQuery_join = 
            array_key_exists('join',$subQuery) && is_array($subQuery['join'])? 
            $subQuery['join'] : "";

        //check if key 'where' exist and if it is an array. if true then pass the value to $join 
        $subQuery_where =    
            array_key_exists('where',$subQuery) && is_array($subQuery['where'])? 
            $subQuery['where'] : "";

        //check if key 'group_by' exist and if it is an array. if true then pass the value to $join 
        $subQuery_group_by = 
            array_key_exists('group_by',$subQuery) && is_array($subQuery['group_by'])? 
            $subQuery['group_by']: "";

        //check if key 'having' exist and if it is an array. if true then pass the value to $join 
        $subQuery_having = 
            array_key_exists('having',$subQuery) && is_array($subQuery['having'])? 
            $subQuery['having'] : "";

        //check if key 'order_by' exist and if it is an array. if true then pass the value to $join 
        $subQuery_order_by = 
            array_key_exists('order_by',$subQuery) && is_array($subQuery['order_by'])? 
            $subQuery['order_by'] : "";

        //check if key 'limit_offset' exist and if it is an array. if true then pass the value to $join 
        $subQuery_limit_offset = 
            array_key_exists('limit_offset',$subQuery) && is_array($subQuery['limit_offset'])? 
            $subQuery['limit_offset'] : "";

        /*****builder pattern creation*******/

        $subQueryBuilder = new SubQueryBuilder($subQuery_select, $subQuery_join, $subQuery_where, $subQuery_group_by, $subQuery_having, $subQuery_order_by, $subQuery_limit_offset);

        $director = new Director();

        $director->build($subQueryBuilder);

        return $subQuery = $subQueryBuilder->getQuery()->showQuery();

       
    }
}

/*****factory pattern creation*******/
// $sqlQuery = SqlQueryBuilder::$command($subQuery)->getQuery();

// return $sqlQuery;