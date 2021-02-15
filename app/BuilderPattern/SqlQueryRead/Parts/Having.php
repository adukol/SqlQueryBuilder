<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

class Having{

    private $having;
    private $having_stmt;
    private $result_having_stmt;

    public function __construct($p_having)
    {
        $this->having = $p_having;
        $this->having_stmt = " HAVING ";
    }

    private function createHaving(){

        $this->having_stmt .= $this->having['agg_function']."(".$this->having['column_name'] .") ". $this->having['operator'] ." '".$this->having['value']."' "  ;

        return $this->having_stmt;
    }

    public function getHavingStmt(){

        $this->result_having_stmt = $this->createHaving();

        return trim($this->result_having_stmt);
    }
}