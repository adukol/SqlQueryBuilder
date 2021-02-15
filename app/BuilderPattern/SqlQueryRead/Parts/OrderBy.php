<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

class OrderBy{

    private $orderBy;
    private $orderBy_stmt;
    private $result_orderBy;
    private $expectedOrder;

    public function __construct($p_orderBy)
    {   
        $this->orderBy = $p_orderBy;
        $this->orderBy_stmt = "";

        $this->expectedOrder = ['ASC','DESC'];
    }

    private function createOrderBy(){

        if(in_array(strToUpper($this->orderBy['order']),$this->expectedOrder)){
            $this->orderBy_stmt .= " ORDER BY ".  $this->orderBy['table'].".".$this->orderBy['column']." ".strToUpper($this->orderBy['order']);
        } 
        return $this->orderBy_stmt;
    }

    public function getOrderByStmt(){

        $this->result_orderBy = $this->createOrderBy();

        return trim($this->result_orderBy);
    }
}