<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

class Join{

    private $joins;
    private $from_stmt;
    private $on_stmt;
    private $join_stmt;
    private $result_join_stmt;

    public function __construct($p_join)
    {
        $this->joins = $p_join;
        $this->from_stmt = " FROM ";
        $this->on_stmt = " ON ";
    }

    private function createJoin(){
        
        // loop through the join detail
        foreach ($this->joins as $key_join => $join) {

            if($key_join == 0){

                $this->from_stmt .= $join['join_from'] ." AS ". $join['join_from'] ." ". strtoupper($join['join_name']) ." ". $join['join_to'] ." AS ". $join['join_to'];
                $this->on_stmt .= $join['join_from'].".".$join['join_key']." = ".$join['join_to'].".".$join['join_key'] . " ";

            } else{

                $this->on_stmt .= strtoupper($join['join_name']) ." ". $join['join_to'] ." AS ". $join['join_to'] ." ON ". $join['join_from'].".".$join['join_key']." = ".$join['join_to'].".".$join['join_key'];
            }
        }

        $this->join_stmt = $this->from_stmt . $this->on_stmt;

        return $this->join_stmt;
    }

    public function getJoinStmt(){

        $this->result_join_stmt = $this->createJoin();

        return trim($this->result_join_stmt);
    }
}