<?php declare(strict_types=1);

namespace App\BuilderPattern\SqlQueryRead\MainQuery;

use Illuminate\Support\Facades\DB;

class MainQuery{

    private $queryParts;
    private $query;

    public function __construct()
    {
        $this->queryParts = [];
        $this->query = "";
    }

    protected function setPart(string $key, string $value)
    {
        $this->queryParts[$key] = $value; 
    }

    protected function getDataFromQuery(){

        $this->query =  implode(' ', $this->queryParts);
        
        $dataToReturn = [];
        try {

            $dataFromDb = DB::select($this->query);
            $dataToReturn = ['status'=>'success', 'code'=>'201', 'query' => $this->query ,'data'=> $dataFromDb];

        } catch (\Throwable $th) {

            $dataToReturn = ['status'=>'error', 'code'=>'500','query' => $this->query ,'data'=>'Sql Query is not valid'];
            
            // $dataToReturn = ['status'=>'error', 'code'=>'500','query' => $this->query ,'data'=>$th->getMessage()];
        }

        return $dataToReturn;
    }
}   