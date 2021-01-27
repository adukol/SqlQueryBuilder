<?php

    namespace App\Component\SqlQuery;

    use Illuminate\Support\Facades\DB;
    use Exception;

    class SqlQueryRead {

        private $query;
        private $tables;
        private $join_details;
        private $wheres;
        private $group_by;
        private $having;
        private $order_by;
        private $limit_offset;
        private $seperator;

        public function __construct($p_query_data){

            $this->query = "SELECT ";
            $this->tables = $p_query_data['table'];
            $this->join_details = $p_query_data['join_detail'];
            $this->wheres = $p_query_data['where'];
            $this->group_by = $p_query_data['group_by'];
            $this->having = $p_query_data['having'];
            $this->order_by = $p_query_data['order_by'];
            $this->limit_offset = $p_query_data['limit_offset'];
            $this->seperator = ", ";
        }

        public function getQuery(){

            $dataToReturn = [];
            
            try{

                $createdQuery = $this->createQuery();

                $datafrom_db = DB::select($createdQuery);

                $dataToReturn = ['status'=>'success', 'code'=>'201', 'query' => $createdQuery ,'data'=> $datafrom_db];
                // return response()->json($query,201);

            } catch(Exception $exception){

                $dataToReturn = ['status'=>'error', 'code'=>'500','query' => $createdQuery ,'data'=>'Sql Query is not valid'];
                //'message'=>$exception->getMessage()
            }

            return $dataToReturn;
        }

        private function createQuery(){

            $arr_tables = $this->tables;

            foreach ($arr_tables as $key_table => $arr_table) {
                $table_name = $arr_table['name'];
                $columns = $arr_table['column'];

                if($columns != []){
                    foreach ($columns as $key_column => $column) {
                        if($key_table == 0){

                            if($key_column == 0){

                                $this->query .= $column['agg_function'] != ""? $column['agg_function'] ."(". $table_name .".". $column['column_name'] .")": $table_name.".".$column['column_name'];
                            } else{
                                $this->query .= $column['agg_function'] != ""? $this->seperator . $column['agg_function'] ."(". $table_name .".". $column['column_name'] .")": $this->seperator . $table_name.".".$column['column_name'];
                            }
                        } else{

                            $this->query .= $column['agg_function'] != ""? $this->seperator . $column['agg_function'] ."(". $table_name .".". $column['column_name'] .")": $this->seperator . $table_name.".".$column['column_name'];
                        }
                    }
                }
            }

            if(sizeOf($arr_tables) == 1){
                $this->query .= " FROM ". $arr_tables[0]['name'];
            } else{
                $this->query .= $this->setJoinDetails();
            }

            if($this->wheres){

                $this->query .= $this->setWhereDetails();
            }

            if($this->group_by){

                $this->query .= $this->setGroupByDetails();
            }

            if($this->having){

                $this->query .= $this->setHavingDetails();
            }

            if($this->order_by){

                $this->query .= $this->setOrderByDetails();   
            }

            if($this->limit_offset){

                $this->query .= $this->setLimitOffsetDetails();   
            }

            return $this->query;
        }

        private function setJoinDetails(){

            $from_stmt = " FROM ";
            $on_stmt = " ON ";
            
            foreach ($this->join_details as $key_join_detail => $join_detail) {

                if($key_join_detail == 0){

                    $from_stmt .= $join_detail['join_from'] ." AS ". $join_detail['join_from'] ." ". strtoupper($join_detail['join_name']) ." ". $join_detail['join_to'] ." AS ". $join_detail['join_to'];
                    $on_stmt .= $join_detail['join_from'].".".$join_detail['join_key']." = ".$join_detail['join_to'].".".$join_detail['join_key'] . " ";

                } else{

                    $on_stmt .= strtoupper($join_detail['join_name']) ." ". $join_detail['join_to'] ." AS ". $join_detail['join_to'] ." ON ". $join_detail['join_from'].".".$join_detail['join_key']." = ".$join_detail['join_to'].".".$join_detail['join_key'];
                }
            }

            $join_stmt = $from_stmt . $on_stmt;

            return $join_stmt;
        }

        private function setWhereDetails(){
            // return json_encode($this->wheres);

            $operatorWithManyParameterColumn = ['between','in'];
            
            $where_stmt = " WHERE ";

            $parameters = $this->wheres['parameter'];
            $logical_connector = $this->wheres['logical_connector'];

            if($logical_connector){

                foreach ($parameters as $key_parameter => $parameter) {

                    if($key_parameter != 0){
                        $where_stmt .= " ". strtoupper($logical_connector[$key_parameter - 1]). " ";
                    }

                    if(in_array($parameter['operator'],$operatorWithManyParameterColumn)){

                        $where_stmt .= $parameter['column_name'] ." ". strtoupper($parameter['operator']) ." (";

                        $values = $parameter['value'];

                        foreach($values as $key_value => $value){

                            $where_stmt .= $key_value == sizeof($values) - 1? $this->seperator ."'".$value."')": "'".$value."' ";
                        }

                    } else{

                        $where_stmt .= $parameter['column_name'] ." ". strtoupper($parameter['operator']) ." '".$parameter['value']."'";
                    }
                }

            } else{

                if(in_array($parameters[0]['operator'],$operatorWithManyParameterColumn)){

                    $where_stmt .=  $parameters[0]['column_name'] ." ". strtoupper($parameters[0]['operator']) . " (";

                    $values = $parameters[0]['value'];

                    foreach($values as $key_value => $value){

                        $where_stmt .= $key_value == sizeof($values) - 1? $this->seperator ."'".$value."')": "'".$value."' ";
                    }
                    
                } else{

                    $where_stmt .= $parameters[0]['column_name'] ." ". strtoupper($parameters[0]['operator']) ." '".$parameters[0]['value']."' ";
                }
            }

            return $where_stmt;
        }

        private function setHavingDetails(){
            $having_stmt = " HAVING ";

            $having_stmt .= $this->having['agg_function']."(".$this->having['column_name'] .") ". $this->having['operator'] ." '".$this->having['value']."' "  ;

            return $having_stmt;
        }

        private function setGroupByDetails(){
            $groupBy_stmt = " GROUP BY ";

            $groupBy_stmt .= $this->group_by['column_name'];

            return $groupBy_stmt;
        }

        private function setOrderByDetails(){

            $order_by_stmt = " ORDER BY ";

            $order_by_stmt .= $this->order_by['column']." ". strtoupper($this->order_by['order']);

            return $order_by_stmt;
        }

        private function setLimitOffsetDetails(){

            $limit_offset_stmt = "";

            if($this->limit_offset['limit'] == "" && $this->limit_offset['offset'] == ""){
                return $limit_offset_stmt;
            } 

            if($this->limit_offset['limit'] != "" && $this->limit_offset['offset'] == ""){
                $limit_offset_stmt .= ' LIMIT ' . $this->limit_offset['limit'];
            }

            if($this->limit_offset['limit'] != ""  && $this->limit_offset['offset'] != ""){
                $limit_offset_stmt .= ' LIMIT ' . $this->limit_offset['limit'];
                $limit_offset_stmt .= ' OFFSET ' . $this->limit_offset['offset'];
            }

            return $limit_offset_stmt;
            
        }
    }
    
