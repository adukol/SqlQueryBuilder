<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead;

// use SqlQueryRead\Parts\Query;
use SqlQueryRead\Parts\MainQuery;

interface BuilderInterface
{
    // public function createQuery();

    // public function createMainQuery();

    public function setSelect();

    public function setjoin();

    public function setWhere();

    public function setGroupBy();

    public function setHaving();

    public function setOrderBy();

    public function setLimitOffset();

    // public function getQuery(): MainQuery;
}