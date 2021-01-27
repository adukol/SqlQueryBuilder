<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class SqlQueryReadTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_SERVER_ERROR = 500;
    
    const HTTP_URL = 'api/create_sql_query';
    const HTTP_METHOD_POST = 'POST';

     
    #HTTP_BAD_REQUEST--OR---HTTP_UNPROCESSABLE_ENTITY-----------------------------------------------
    public function test_blankRequest(){

        $request = [];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);

        $response->seeStatusCode(self::HTTP_UNPROCESSABLE_ENTITY);
        
        $expected_json = [
            'status'=>'error',
            'message'=> 'Invalid or Empty Request Details'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_blankCommand(){

        $request = [
            "command"=>"",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_id"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>"0"
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);

        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
            'message'=> 'Missing Command Name'
        ]; 

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_blankTableAndColumns(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>"0"
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
            'message'=> 'Invalid Number of Tables'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_blankQueryData(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
            'message'=> 'Missing Query Data'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_invalidCommandName(){

        $request = [
            "command"=>"write",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_id"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
             'message'=> 'Invalid Command Name'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    #HTTP_CREATED ----------------------------------------------------------------------------------

    public function test_singleTableSelect_with_limit() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_id"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>"0"
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            "status"=>"success",
            "query"=>"SELECT profile.profile_id, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName FROM profile LIMIT 2 OFFSET 0",
            "data"=>[
                        [
                            "profile_id"=> 1,
                            "profile_firstName"=> "vUmpKO4qLL",
                            "profile_middleName"=>"XKh5YZNlln",
                            "profile_lastName"=>"5ZP4bfHwWF"
                        ],
                        [
                            "profile_id"=>2,
                            "profile_firstName"=>"Zs6eWE5E5q",
                            "profile_middleName"=>"0U3SlH27uv",
                            "profile_lastName"=>"A1UampnWwP"
                        ]
                    ]
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_orderBy_and_limit() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_id"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_nationality"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_sex"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[
                    "column"=> "profile_id",
                    "order"=> "desc"
                ],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>""
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
                'status' => 'success',
                'query' => 'SELECT profile.profile_id, profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex FROM profile ORDER BY profile_id DESC LIMIT 2',
                'data' => [
                    [
                        'profile_id' => 50,
                        'profile_nationality' => 'Filipino',
                        'profile_firstName' => '1HJQ3p4B0m',
                        'profile_middleName' => 'VNdPI8LpPg',
                        'profile_lastName' => 'SRGeF1U8mU',
                        'profile_sex' => 'Female',
                    ],
                    [
                        'profile_id' => 49,
                        'profile_nationality' => 'Filipino',
                        'profile_firstName' => 'aeqjbbMKys',
                        'profile_middleName' => 'tU8moYEzTu',
                        'profile_lastName' => '1cXrySMsEk',
                        'profile_sex' => 'Male',
                    ]
                ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_where_orderBy_and_limit() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_id"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_nationality"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_sex"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[
                    "parameter"=> [
                        [
                            "column_name"=> "profile_nationality",
                            "operator"=> "=",
                            "value"=> "Filipino"
                        ]
                    ],
                    "logical_connector"=> [
                       
                    ]
                ],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[
                    "column"=> "profile_id",
                    "order"=> "desc"
                ],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>""
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_id, profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex FROM profile WHERE profile_nationality = \'Filipino\'  ORDER BY profile_id DESC LIMIT 2',
            'data' => [
              0 => [
                'profile_id' => 50,
                'profile_nationality' => 'Filipino',
                'profile_firstName' => '1HJQ3p4B0m',
                'profile_middleName' => 'VNdPI8LpPg',
                'profile_lastName' => 'SRGeF1U8mU',
                'profile_sex' => 'Female',
              ],
              1 => [
                'profile_id' => 49,
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'aeqjbbMKys',
                'profile_middleName' => 'tU8moYEzTu',
                'profile_lastName' => '1cXrySMsEk',
                'profile_sex' => 'Male',
              ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_id"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                        ]
                    ],
                    [
                        "name"=>"passport_detail",
                        "column"=> [
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_passportNumber",
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_country",
                            ]
                        ]
                    ],
                    [
                        "name"=> "manifest",
                        "column"=> [
                            [
                                "agg_function"=>"",
                                "column_name"=>"manifest_airlineNumber",
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"manifest_airlineCode",
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"manifest_flightNo",
                            ]
                        ]
                    ]
                ],
                "join_detail"=>[
                    [
                        "join_from"=>"profile",
                        "join_to"=>"passport_detail",
                        "join_name"=>"join",
                        "join_key"=>"profile_id"
                    ],
                    [
                        "join_from"=>"profile",
                        "join_to"=>"manifest",
                        "join_name"=>"join",
                        "join_key"=>"profile_id"
                    ]
                ],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>"0"
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            "status"=>"success",
            "query"=>"SELECT profile.profile_id, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, passport_detail.pd_passportNumber, passport_detail.pd_country, manifest.manifest_airlineNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id LIMIT 2 OFFSET 0",
            "data"=>[
                [
                    "profile_id"=> 1,
                    "profile_firstName"=> "vUmpKO4qLL",
                    "profile_middleName"=> "XKh5YZNlln",
                    "profile_lastName"=> "5ZP4bfHwWF",
                    "pd_passportNumber"=> "Lxhi5J6Ijt1",
                    "pd_country"=> "China",
                    "manifest_airlineNumber"=> "NpSHHlF3w61",
                    "manifest_airlineCode"=> "dPN9wyZVcg",
                    "manifest_flightNo"=> "WgMisnyBCK"
                ],
                [
                    "profile_id"=> 2,
                    "profile_firstName"=> "Zs6eWE5E5q",
                    "profile_middleName"=> "0U3SlH27uv",
                    "profile_lastName"=> "A1UampnWwP",
                    "pd_passportNumber"=> "1dBW78uMpY2",
                    "pd_country"=> "Philippines",
                    "manifest_airlineNumber"=> "GvrzA3ofgy2",
                    "manifest_airlineCode"=> "QLre82ahDn",
                    "manifest_flightNo"=> "MBN5xFCJMH"
                ]
            ]
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect__with_multipleWhereCondition() {
    
        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_nationality"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_firstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_middleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_lastName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_sex"
                            ],
                        ]
                    ],
                    [
                        "name"=>"passport_detail",
                        "column"=> [
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_passportNumber",
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_country",
                            ]
                        ]
                    ],
                    [
                        "name"=> "manifest",
                        "column"=> [
                            [
                                "agg_function"=>"",
                                "column_name"=>"manifest_airlineNumber",
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"manifest_airlineCode",
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"manifest_flightNo",
                            ]
                        ]
                    ]
                ],
                "join_detail"=>[
                    [
                        "join_from"=>"profile",
                        "join_to"=>"passport_detail",
                        "join_name"=>"join",
                        "join_key"=>"profile_id"
                    ],
                    [
                        "join_from"=>"profile",
                        "join_to"=>"manifest",
                        "join_name"=>"join",
                        "join_key"=>"profile_id"
                    ]
                ],
                "where"=>[
                    "parameter"=> [
                        [
                            "column_name"=> "profile_sex",
                            "operator"=> "=",
                            "value"=> "Male"
                        ],
                        [
                            "column_name"=> "profile_nationality",
                            "operator"=> "=",
                            "value"=> "Filipino"
                        ]
                    ],
                    "logical_connector"=> [
                        "and"
                    ]
                ],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>"0"
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, passport_detail.pd_country, manifest.manifest_airlineNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_sex = \'Male\' AND profile_nationality = \'Filipino\' LIMIT 2 OFFSET 0',
            'data' => [
                [
                    'profile_nationality' => 'Filipino',
                    'profile_firstName' => 'GrirmTLtAR',
                    'profile_middleName' => 'CSxn0lJslx',
                    'profile_lastName' => 'uRXu4LGKBE',
                    'profile_sex' => 'Male',
                    'pd_passportNumber' => 'idRqP2f8tx6',
                    'pd_country' => 'Korea',
                    'manifest_airlineNumber' => 'fOSn4ADCGu6',
                    'manifest_airlineCode' => 'WfN7S5zc8u',
                    'manifest_flightNo' => '9GPCR6wp9s',
                ],
                [
                    'profile_nationality' => 'Filipino',
                    'profile_firstName' => 'ur8OVwyCsR',
                    'profile_middleName' => '5oXEOMSLe2',
                    'profile_lastName' => 'lZC9UpJYgL',
                    'profile_sex' => 'Male',
                    'pd_passportNumber' => 'x5DuhhaBNW7',
                    'pd_country' => 'Japan',
                    'manifest_airlineNumber' => 'ocuryPnv077',
                    'manifest_airlineCode' => 'mxtmIlgJy5',
                    'manifest_flightNo' => 'mVSCZRZq9M',
                ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_aggregateFunction_and_groupBy(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_nationality"
                            ],
                            [
                                "agg_function"=>"count",
                                "column_name"=>"profile_id"
                            ]
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[

                    "column_name"=>"profile_nationality"
                ],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>""
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json =[
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, count(profile.profile_id) FROM profile GROUP BY profile_nationality LIMIT 2',
            'data' => [
                [
                    'profile_nationality' => 'Chinese',
                    'count(profile.profile_id)' => 5,
                ],
                [
                    'profile_nationality' => 'Filipino',
                    'count(profile.profile_id)' => 8,
                ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_aggregateFunction_groupBy_having(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_nationality"
                            ],
                            [
                                "agg_function"=>"count",
                                "column_name"=>"profile_id"
                            ]
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[
    
                    "column_name"=>"profile_nationality"
                ],
                "having"=>[
                    "agg_function"=>"count",
                    "column_name"=>"profile_id",
                    "operator"=>">",
                    "value"=>"10"
                ],
                "order_by"=>[],
                "limit_offset"=>[]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, count(profile.profile_id) FROM profile GROUP BY profile_nationality HAVING count(profile_id) > \'10\' ',
            'data' => [
                [
                    'profile_nationality' => 'Italian',
                    'count(profile.profile_id)' => 15,
                ],
                [
                    'profile_nationality' => 'Japanese',
                    'count(profile.profile_id)' => 11,
                ],
                [
                    'profile_nationality' => 'Korean',
                    'count(profile.profile_id)' => 11,
                ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect_with_aggregateFunction_and_groupBy(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                                [
                                    "agg_function"=>"count",
                                    "column_name"=>"profile_id"
                                ],
                            ]
                    ],
                    [
                        "name"=>"passport_detail",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_country"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[
                        [
                            'join_from' => 'profile',
                            'join_to' => 'passport_detail',
                            'join_name' => 'join',
                            'join_key' => 'profile_id',
                        ]
                  ],
                "where"=>[],
                "group_by"=>[
                    "column_name"=>"pd_country"
                ],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>""
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT count(profile.profile_id), passport_detail.pd_country FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id  GROUP BY pd_country LIMIT 2',
            'data' => [
                [
                    'count(profile.profile_id)' => 1,
                    'pd_country' => 'China',
                ],
                [
                    'count(profile.profile_id)' => 5,
                    'pd_country' => 'Italy',
                ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect_with_multipleWhereCondition_of_multipleParameter(){

        $request = [
            'command' => 'read',
            'query_data' => [
              'table' => [
                    [
                        'name' => 'profile',
                        'column' => [
                        [
                            'agg_function' => '',
                            'column_name' => 'profile_nationality',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_firstName',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_middleName',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_lastName',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_sex',
                            ],
                        ],
                    ],
                    [
                        'name' => 'passport_detail',
                        'column' => [
                            0 => [
                            'agg_function' => '',
                            'column_name' => 'pd_passportNumber',
                            ],
                            1 => [
                            'agg_function' => '',
                            'column_name' => 'pd_country',
                            ],
                        ],
                    ],
                    [
                        'name' => 'manifest',
                        'column' => [
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_airlineNumber',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_airlineCode',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_flightNo',
                            ],
                    ],
                ],
              ],
              'join_detail' => [
                    [
                    'join_from' => 'profile',
                    'join_to' => 'passport_detail',
                    'join_name' => 'join',
                    'join_key' => 'profile_id',
                    ],
                    [
                    'join_from' => 'profile',
                    'join_to' => 'manifest',
                    'join_name' => 'join',
                    'join_key' => 'profile_id',
                    ],
              ],
              'where' => [
                'parameter' => [
                    [
                        'column_name' => 'profile_sex',
                        'operator' => '=',
                        'value' => 'Male',
                    ],
                    [
                        'column_name' => 'profile_nationality',
                        'operator' => 'in',
                        'value' => [
                            'Filipino',
                            'Chinese',
                        ],
                    ],
                    [
                        'column_name' => 'manifest_airlineNumber',
                        'operator' => 'like',
                        'value' => '%Gu6',
                    ],
                ],
                'logical_connector' => [
                    'and',
                    'and',
                ],
              ],
              'group_by' => [],
              'having' => [],
              'order_by' => [],
              'limit_offset' => [],
            ],
          ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, passport_detail.pd_country, manifest.manifest_airlineNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_sex = \'Male\' AND profile_nationality IN (\'Filipino\' , \'Chinese\') AND manifest_airlineNumber LIKE \'%Gu6\'',
            'data' => [
                [
                    'profile_nationality' => 'Filipino',
                    'profile_firstName' => 'GrirmTLtAR',
                    'profile_middleName' => 'CSxn0lJslx',
                    'profile_lastName' => 'uRXu4LGKBE',
                    'profile_sex' => 'Male',
                    'pd_passportNumber' => 'idRqP2f8tx6',
                    'pd_country' => 'Korea',
                    'manifest_airlineNumber' => 'fOSn4ADCGu6',
                    'manifest_airlineCode' => 'WfN7S5zc8u',
                    'manifest_flightNo' => '9GPCR6wp9s',
                ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect_with_singleWhereCondition_of_multipleParameter(){

        $request = [
            'command' => 'read',
            'query_data' => [
              'table' => [
                    [
                        'name' => 'profile',
                        'column' => [
                        [
                            'agg_function' => '',
                            'column_name' => 'profile_nationality',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_firstName',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_middleName',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_lastName',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'profile_sex',
                            ],
                        ],
                    ],
                    [
                        'name' => 'passport_detail',
                        'column' => [
                            0 => [
                            'agg_function' => '',
                            'column_name' => 'pd_passportNumber',
                            ],
                            1 => [
                            'agg_function' => '',
                            'column_name' => 'pd_country',
                            ],
                        ],
                    ],
                    [
                        'name' => 'manifest',
                        'column' => [
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_airlineNumber',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_airlineCode',
                            ],
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_flightNo',
                            ],
                    ],
                ],
              ],
              'join_detail' => [
                    [
                    'join_from' => 'profile',
                    'join_to' => 'passport_detail',
                    'join_name' => 'join',
                    'join_key' => 'profile_id',
                    ],
                    [
                    'join_from' => 'profile',
                    'join_to' => 'manifest',
                    'join_name' => 'join',
                    'join_key' => 'profile_id',
                    ],
              ],
              'where' => [
                'parameter' => [
                    [
                        'column_name' => 'profile_nationality',
                        'operator' => 'in',
                        'value' => [
                            'Filipino',
                            'Chinese',
                        ],
                    ]
                ],
                'logical_connector' => [],
              ],
              'group_by' => [],
              'having' => [],
              'order_by' => [],
              'limit_offset' => [
                    "limit"=>"2",
                    "offset"=>""

              ],
            ],
          ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, passport_detail.pd_country, manifest.manifest_airlineNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_nationality IN (\'Filipino\' , \'Chinese\') LIMIT 2',
            'data' => [
              0 => [
                'profile_nationality' => 'Chinese',
                'profile_firstName' => 'Zs6eWE5E5q',
                'profile_middleName' => '0U3SlH27uv',
                'profile_lastName' => 'A1UampnWwP',
                'profile_sex' => 'Female',
                'pd_passportNumber' => '1dBW78uMpY2',
                'pd_country' => 'Philippines',
                'manifest_airlineNumber' => 'GvrzA3ofgy2',
                'manifest_airlineCode' => 'QLre82ahDn',
                'manifest_flightNo' => 'MBN5xFCJMH',
              ],
              1 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'GrirmTLtAR',
                'profile_middleName' => 'CSxn0lJslx',
                'profile_lastName' => 'uRXu4LGKBE',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'idRqP2f8tx6',
                'pd_country' => 'Korea',
                'manifest_airlineNumber' => 'fOSn4ADCGu6',
                'manifest_airlineCode' => 'WfN7S5zc8u',
                'manifest_flightNo' => '9GPCR6wp9s',
              ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    #HTTP_SERVER_ERROR -------------------------------------------------------------------------------

    public function test_invalidColumnNames(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "table"=>[
                    [
                        "name"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"proadwadawfile_natioadwadanality"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profiawdawdawle_firadwadawdstName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profiawdawdawle_miadwadawddleName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profadawdile_lastawdawdawName"
                            ],
                            [
                                "agg_function"=>"",
                                "column_name"=>"profiladawdawdwae_sex"
                            ],
                        ]
                    ]
                ],
                "join_detail"=>[],
                "where"=>[],
                "group_by"=>[],
                "having"=>[],
                "order_by"=>[],
                "limit_offset"=>[
                    "limit"=>"",
                    "offset"=>""
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request);
        $response->seeStatusCode(self::HTTP_SERVER_ERROR);

        $expected_json = [
            'status'=>'error',
            'data'=>'Sql Query is not valid'
        ];

        $response->seeJsonContains($expected_json);
        
    }
}
