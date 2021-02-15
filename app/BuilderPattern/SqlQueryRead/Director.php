<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead;

use App\BuilderPattern\SqlQueryRead\BuilderInterface;

class Director
{
    public function build(BuilderInterface $builder)
    {
        $builder->setSelect();
        $builder->setJoin();
        $builder->setWhere();
        $builder->setGroupBy();
        $builder->setHaving();
        $builder->setOrderBy();
        $builder->setLimitOffset();   
        
        // return $builder->getQuery();
    }


    // private $builder;

    // public function setBuilder(BuilderInterface $builder): void
    // {
    //     $this->builder = $builder;
    // }

    // public function buildQuery(): void
    // {
    //     $this->builder->setSelect();
    //     $this->builder->setJoin();
    //     $this->builder->setWhere();
    //     $this->builder->setGroupBy();
    //     $this->builder->setHaving();
    //     $this->builder->setOrderBy();
    //     $this->builder->setLimitOffset();        
    // }
}


    

