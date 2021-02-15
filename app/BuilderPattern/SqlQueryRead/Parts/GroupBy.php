<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

class GroupBy{

    private $group_by;
    private $groupBy_stmt;
    private $result_groupBy_stmt;

    public function __construct($p_group_by)
    {
        $this->group_by = $p_group_by;
        $this->groupBy_stmt = " GROUP BY ";
    }

    private function createGroupBy(){

        $this->groupBy_stmt .= $this->group_by['column_name'];

        return $this->groupBy_stmt;
    }

    public function getGroupByStmt(){

        $this->result_groupBy_stmt = $this->createGroupBy();

        return trim($this->result_groupBy_stmt);
    }

}