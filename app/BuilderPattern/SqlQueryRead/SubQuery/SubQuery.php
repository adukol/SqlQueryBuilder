<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\SubQuery;

class SubQuery{

    private $queryParts = [];
    private $query;

    protected function setPart(string $key, string $value)
    {
        $this->queryParts[$key] = $value; 
    }

    public function showQuery()
    {
        $this->query =  implode(' ', $this->queryParts);

        return $this->query;
    }
}   