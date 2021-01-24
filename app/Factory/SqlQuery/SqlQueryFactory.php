<?php

    namespace App\Factory\SqlQuery;

    use App\Factory\SqlQuery\SqlQueryRead;

    class SqlQueryFactory  {
        
        protected static function read($p_query_data){

            return new SqlQueryRead($p_query_data);
        }
    }