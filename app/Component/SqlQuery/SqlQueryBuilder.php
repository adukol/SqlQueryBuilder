<?php

    namespace App\Component\SqlQuery;

    use App\Component\SqlQuery\SqlQueryRead;

    class SqlQueryBuilder  {
        
        protected static function read($p_query_data){

            return new SqlQueryRead($p_query_data);
        }
    }