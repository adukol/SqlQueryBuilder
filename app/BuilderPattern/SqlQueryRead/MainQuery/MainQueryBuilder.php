<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\MainQuery;

use App\BuilderPattern\SqlQueryRead\BuilderInterface;
use App\BuilderPattern\SqlQueryRead\MainQuery\MainQuery;

use App\BuilderPattern\SqlQueryRead\Parts\Select;
use App\BuilderPattern\SqlQueryRead\Parts\Join;
use App\BuilderPattern\SqlQueryRead\Parts\Where;
use App\BuilderPattern\SqlQueryRead\Parts\GroupBy;
use App\BuilderPattern\SqlQueryRead\Parts\Having;
use App\BuilderPattern\SqlQueryRead\Parts\OrderBy;
use App\BuilderPattern\SqlQueryRead\Parts\LimitOffset;

class MainQueryBuilder extends MainQuery implements BuilderInterface{

    private $mainQuery;

    private $select;
    private $join;
    private $where;
    private $group_by;
    private $having;
    private $order_by;
    private $limit_offset;

    public function __construct($p_select = null, $p_join = null, $p_where = null, $p_group_by = null, $p_having = null, $p_order_by = null, $p_limit_offset = null)
    {
        
        $this->reset();

        $this->select = $p_select;
        $this->join = $p_join;
        $this->where = $p_where;
        $this->group_by = $p_group_by;
        $this->having = $p_having;
        $this->order_by = $p_order_by;
        $this->limit_offset = $p_limit_offset;
        
    }

    public function reset(): void
    {
        $this->mainQuery = new MainQuery();
    }

    public function setSelect()
    {
        if(count($this->select) && $this->select){
            $selectStmt = new Select($this->select);
            $this->mainQuery->setPart('select', $selectStmt->getSelectStmt());
        }
    }

    public function setJoin()
    {
        if($this->join && count($this->select) != 1){
            $joinStmt = new Join($this->join);
            $this->mainQuery->setPart('join', $joinStmt->getJoinStmt());
        }
    }

    public function setWhere()
    {
        if($this->where){
            $whereStmt = new Where($this->where);
            $this->mainQuery->setPart('where', $whereStmt->getWhereStmt());
        }
    }

    public function setGroupBy()
    {
        if($this->group_by){
            $groupByStmt = new GroupBy($this->group_by);
            $this->mainQuery->setPart('group_by', $groupByStmt->getGroupByStmt());
        }
    }

    public function setHaving()
    {
        if($this->having){
            $havingStmt = new Having($this->having);
            $this->mainQuery->setPart('having', $havingStmt->getHavingStmt());
        }
    }

    public function setOrderBy()
    {
        if($this->order_by){
            $orderByStmt = new OrderBy($this->order_by);
            $this->mainQuery->setPart('order_by', $orderByStmt->getorderByStmt());
        }
    }

    public function setLimitOffset()
    {
        if($this->limit_offset){
            $limitOffsetStmt = new LimitOffset($this->limit_offset);
            $this->mainQuery->setPart('limit_offset', $limitOffsetStmt->getLimitOffsetStmt());
        }
    }

    public function getQuery(): MainQuery
    {
        $returningQuery = $this->mainQuery;
        $this->reset();
        return $returningQuery;
        
        // $dataToReturn = [];
            
        // //try to run the query to get the data
        // try{

        //     $datafrom_db = DB::select($this->result);

        //     $dataToReturn = ['status'=>'success', 'code'=>'201', 'query' => $this->query ,'data'=> $datafrom_db];
        //     // return response()->json($query,201);

        // } catch(Exception $exception){

        //     $dataToReturn = ['status'=>'error', 'code'=>'500','query' => $this->query ,'data'=>'Sql Query is not valid'];
        //     //'message'=>$exception->getMessage()
        // }

        // $result = $this->mainQuery;
        // $this->reset();

        // return  $this->query;

        // return $result;
        

    }


}

