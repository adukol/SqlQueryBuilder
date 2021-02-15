<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\Parts;

class LimitOffset{

    private $limit_offset;
    private $limit_offset_stmt;
    private $result_limit_offset_stmt;

    public function __construct($p_limit_offset)
    {
        $this->limit_offset = $p_limit_offset;
        $this->limit_offset_stmt = "";
    }

    private function createLimitOffset(){

        $type_limit = gettype($this->limit_offset['limit']);
        $type_offset = gettype($this->limit_offset['offset']);

        if($this->limit_offset['limit'] == "" && $this->limit_offset['offset'] == ""){
            $this->limit_offset_stmt = "";
        } 

        if($type_limit == 'integer' && $this->limit_offset['offset'] == ""){
            $this->limit_offset_stmt = ' LIMIT ' . $this->limit_offset['limit'];
        }

        if( $type_limit == 'integer' && $type_offset == 'integer'){
            
            $this->limit_offset_stmt = ' LIMIT ' . $this->limit_offset['limit'];
            $this->limit_offset_stmt .= ' OFFSET ' . $this->limit_offset['offset'];
        }

        return $this->limit_offset_stmt;
    }

    public function getLimitOffsetStmt(){

        $this->result_limit_offset_stmt = $this->createLimitOffset();

        return trim($this->result_limit_offset_stmt);
    }
}