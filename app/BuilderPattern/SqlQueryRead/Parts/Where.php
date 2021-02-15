<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

use App\BuilderPattern\SqlQueryRead\SubQuery\SubQueryValidator;

class Where{

    private $where;
    private $operatorWithManyParameterColumn;
    private $where_stmt;
    private $result_where_stmt;
    private $seperator;

    public function __construct($p_where)
    {
        $this->where = $p_where;
        $this->operatorWithManyParameterColumn = ['between','in'];
        $this->where_stmt = " WHERE ";
        $this->seperator = ", ";
    }

    private function createWhere(){
        
        $parameters = $this->where['parameter'];
        $logical_connector = $this->where['logical_connector'];


        // check if multiple where condition
        if($logical_connector){

            foreach ($parameters as $key_parameter => $parameter) {

                if($key_parameter != 0){
                    $this->where_stmt .= " ". strtoupper($logical_connector[$key_parameter - 1]). " ";
                }

                // check if the condition needs multiple parameter like between or in
                if(in_array($parameter['operator'],$this->operatorWithManyParameterColumn)){

                    $this->where_stmt .= $parameter['column_name'] ." ". strtoupper($parameter['operator']) ." (";

                    $values = $parameter['value'];

                    foreach($values as $key_value => $value){

                        $this->where_stmt .= $key_value == sizeof($values) - 1? $this->seperator ."'".$value."')": "'".$value."' ";
                    }

                } else{

                    $this->where_stmt .= $parameter['column_name'] ." ". strtoupper($parameter['operator']) ." '".$parameter['value']."'";
                }
            }

        } else{

            // check if the condition needs multiple parameter like between or in
            if(is_array($parameters[0]['value']) && in_array($parameters[0]['operator'],$this->operatorWithManyParameterColumn)){

                $this->where_stmt .=  $parameters[0]['column_name'] ." ". strtoupper($parameters[0]['operator']) . " (";

                $values = $parameters[0]['value'];

                foreach($values as $key_value => $value){

                    $this->where_stmt .= $key_value == sizeof($values) - 1? $this->seperator ."'".$value."')": "'".$value."' ";
                }
            }
            
            if(is_array($parameters[0]['value']) && array_key_exists('sub_query_data',$parameters[0]['value'])){

                $this->where_stmt .=  $parameters[0]['column_name'] ." ". strtoupper($parameters[0]['operator']) . " (";

                $subQuery_data = $parameters[0]['value']['sub_query_data']?
                    $parameters[0]['value']['sub_query_data'] : null;
                    
                if($subQuery_data){
                    
                    $subQuery = SubQueryValidator::validate($subQuery_data);

                    $this->where_stmt .=  $subQuery . ")";
                }
            } 

            if(!is_array($parameters[0]['value'])){

                $this->where_stmt .= $parameters[0]['column_name'] ." ". strtoupper($parameters[0]['operator']) ." '".$parameters[0]['value']."' ";
            }
        }

        return $this->where_stmt;
    }

    public function getWhereStmt(){

        $this->result_where_stmt = $this->createWhere();

        return trim($this->result_where_stmt);
    }

}