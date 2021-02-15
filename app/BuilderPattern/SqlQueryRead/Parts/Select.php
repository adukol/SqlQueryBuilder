<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

class Select{

    private $select;
    private $select_stmt;
    private $result_select_stmt;
    private $seperator;

    public function __construct($p_select)
    {
        $this->select = $p_select;
        $this->select_stmt = "SELECT ";
        $this->seperator = ", ";
    }

    private function createSelect(){
        
        $arr_select = $this->select;

        // loop through each tables
        foreach ($arr_select as $key_select => $select) {
            $table = $select['table'];
            $columns = $select['column'];

            if($columns != []){
                // loop through each columns
                foreach ($columns as $key_column => $column) {
                    if($key_select == 0){

                        if($key_column == 0){

                            $this->select_stmt .= $column['agg_function'] != ""? $column['agg_function'] ."(". $table .".". $column['column_name'] .")": $table.".".$column['column_name'];
                        } else{
                            $this->select_stmt .= $column['agg_function'] != ""? $this->seperator . $column['agg_function'] ."(". $table .".". $column['column_name'] .")": $this->seperator . $table.".".$column['column_name'];
                        }
                    } else{

                        $this->select_stmt .= $column['agg_function'] != ""? $this->seperator . $column['agg_function'] ."(". $table .".". $column['column_name'] .")": $this->seperator . $table.".".$column['column_name'];
                    }
                }
            }
            // assump output
            // SELECT profile.profile_id, profile.profile_firstName
        }

        if(sizeOf($arr_select) == 1){
            // single table
            return $this->select_stmt .= " FROM ". $arr_select[0]['table'];
        } else{
            return $this->select_stmt;
        }
    }

    public function getSelectStmt(){

        $this->result_select_stmt = $this->createSelect();

        return trim($this->result_select_stmt);
    }

}