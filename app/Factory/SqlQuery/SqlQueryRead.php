<?php

    namespace App\Factory\SqlQuery;

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

        public function __construct($p_query_data){
            $this->query = "SELECT ";
            $this->tables = $p_query_data['table'];
            $this->wheres = $p_query_data['where'];
            $this->join_details = $p_query_data['join_detail'];
            $this->group_by = $p_query_data['group_by'];
            $this->having = $p_query_data['having'];
            $this->order_by = $p_query_data['order_by'];
            $this->limit_offset = $p_query_data['limit_offset'];
        }

        public function returnQuery(){

            $returnQuery = $this->createQuery();

            try{

                $data = DB::select($returnQuery);

                return array(['status'=>'success','query' => $returnQuery ,'data'=>$data]);
                // return response()->json($query,200);

            } catch(Exception $exception){

                return array(['status'=>'error','query' => $returnQuery ,'message'=>$exception->getMessage()]);
            }
        }

        private function createQuery(){

            $seperator = ", ";

            $arr_tables = $this->tables;

            foreach ($arr_tables as $key_table => $arr_table) {
                $table_name = $arr_table['name'];
                $columns = $arr_table['column'];

                if($columns != []){
                    foreach ($columns as $key_column => $column) {
                        if($key_table == 0){

                            $this->query .= $key_column == 0? $table_name.".".$column:$seperator . $table_name.".".$column;
                        } else{
                            $this->query .=  $seperator . $table_name.".".$column;
                        }
    
                    }
                }
            }

            if(sizeOf($arr_tables) == 1){
                $this->query .= " FROM ". $arr_tables[0]['name'];
            } else{
                $this->query .= $this->createJoinDetails();
            }

            if($this->wheres){

                $this->query .= $this->createWhereDetails();
            }

            if($this->order_by){

                $this->query .= $this->createOrderByDetails();   
            }

            if($this->limit_offset){

                $this->query .= $this->createLimitOffsetDetails();   
            }

            return $this->query;
        }

        private function createJoinDetails(){

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

        private function createWhereDetails(){

            foreach ($this->wheres as $where) {
                $where_stmt = " WHERE ";

                $parameters = $where['parameter'];
                $logical_connector = $where['logical_connector'];

                foreach ($parameters as $key_parameter => $parameter) {
                    if($logical_connector){

                        $where_stmt .= $key_parameter == sizeof($parameters) - 1 ? $parameter['columnName'] ." ". strtoupper($parameter['operator']) ." '".$parameter['value']."' " : $parameter['columnName'] ." ". strtoupper($parameter['operator']) ." '".$parameter['value']."' ". strtoupper($logical_connector[$key_parameter]). " ";

                    } else{

                        $where_stmt .= $parameter['columnName'] ." ". strtoupper($parameter['operator']) ." '".$parameter['value']."' ";
                    }
                }

                return $where_stmt;
            }
        }

        private function createOrderByDetails(){

            $order_by_stmt = "ORDER BY ";

            $order_by_stmt .= $this->order_by['column']." ". strtoupper($this->order_by['order']);

            return $order_by_stmt;
        }

        private function createLimitOffsetDetails(){

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
    
