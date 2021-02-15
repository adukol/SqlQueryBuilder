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
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_SERVER_ERROR = 500;
    
    const HTTP_URL = 'api/create_sql_query';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2Nlc3NUb2tlbiI6IjY1YWYxMGJkMjE5NjA1NzYzOGQzZDQzY2ZlNTc5YjY4NmZmZmIxZGYiLCJhY2Nlc3NUb2tlbkV4cGlyZXNBdCI6IjIwMjEtMDItMTVUMTE6MzM6MzEuMjkyWiIsInJlZnJlc2hUb2tlbiI6Ijc2NGE5YzQ3N2IwNjU5YjQ1ZDc3MDE3MTM4OTU5ZmQ3MWMxYWNmOTkiLCJyZWZyZXNoVG9rZW5FeHBpcmVzQXQiOiIyMDIxLTAzLTAxVDEwOjMzOjMxLjI5MloiLCJjbGllbnQiOnsiaWQiOiJhcHBsaWNhdGlvbiJ9LCJ1c2VyIjp7InVzZXJuYW1lIjoiYWRtaW5Ac2FtcGxlLmNvbSIsInJvbGUiOjF9LCJpYXQiOjE2MTMzODUyMTF9.4bszxWkqRKKWFvMHxpajmgdENNI5ma0mvpf5v3EeW8k';
     
    #HTTP_BAD_REQUEST--OR--HTTP_UNPROCESSABLE_ENTITY--OR--HTTP_UNAUTHORIZED----------------------

    public function test_blankOrWrongAuthorization(){

        $request = [];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer 12312dawdaw'. self::HTTP_TOKEN
                    ]);

        $response->seeStatusCode(self::HTTP_UNAUTHORIZED);
        
        $expected_json = [
            'status'=>'error',
            'message'=> 'Unauthorized Request'
        ];

        $response->seeJsonEquals($expected_json);
        
    }
    public function test_blankRequest(){

        $request = [];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);

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
                "select"=>[
                    [
                        "table"=>"profile",
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
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);

        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
            'message'=> 'Missing or Invalid Command Name'
        ]; 

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_blankSelectDetails(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[],
                "limit_offset"=>[
                    "limit"=>"2",
                    "offset"=>"0"
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                'Accept'=>'application/json',
                'Content-Type'=>'application/json',
                'Authorization'=>'Bearer '. self::HTTP_TOKEN
            ]);
        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
            'message'=> 'Missing or Invalid Select Details'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_blankQueryData(){

        $request = [
            "command"=>"read",
            "query_data"=> []
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
            'message'=> 'Missing or Invalid Query Data'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    public function test_invalidCommandName(){

        $request = [
            "command"=>"write",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        $response->seeStatusCode(self::HTTP_BAD_REQUEST);

        $expected_json = [
            'status'=>'error',
             'message'=> 'Missing or Invalid Command Name'
        ];

        $response->seeJsonEquals($expected_json);
        
    }

    #HTTP_CREATED ----------------------------------------------------------------------------------

    public function test_singleTableSelect_with_limit() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                "limit_offset"=>[
                    "limit"=>2,
                    "offset"=>0
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_id, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName FROM profile LIMIT 2 OFFSET 0',
            'data' => [
              0 => [
                'profile_id' => 1,
                'profile_firstName' => 'Roger',
                'profile_middleName' => 'Yang',
                'profile_lastName' => 'Fletcher',
              ],
              1 => [
                'profile_id' => 2,
                'profile_firstName' => 'Ike',
                'profile_middleName' => 'Lulloff',
                'profile_lastName' => 'Orwig',
              ],
            ],
          ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_orderBy_and_limit() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"profile_age"
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
                            ]
                         
                        ]
                    ]
                ],
                "order_by"=>[
                    "table"=>"profile",
                    "column"=> "profile_age",
                    "order"=> "desc"
                ],
                "limit_offset"=>[
                    "limit"=>5,
                    "offset"=>0
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_age, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName FROM profile ORDER BY profile.profile_age DESC LIMIT 5 OFFSET 0',
            'data' => [
              0 => [
                'profile_age' => 60,
                'profile_firstName' => 'Walter',
                'profile_middleName' => 'Johnsen',
                'profile_lastName' => 'Ventotla',
              ],
              1 => [
                'profile_age' => 60,
                'profile_firstName' => 'Larry',
                'profile_middleName' => 'Uddin',
                'profile_lastName' => 'LePage',
              ],
              2 => [
                'profile_age' => 59,
                'profile_firstName' => 'Roger',
                'profile_middleName' => 'Yang',
                'profile_lastName' => 'Fletcher',
              ],
              3 => [
                'profile_age' => 57,
                'profile_firstName' => 'Roger',
                'profile_middleName' => 'Solberg',
                'profile_lastName' => 'Lewis',
              ],
              4 => [
                'profile_age' => 57,
                'profile_firstName' => 'Victor',
                'profile_middleName' => 'Reyes',
                'profile_lastName' => 'Quizoz',
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_where_orderBy_and_limit() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                "order_by"=>[
                    "table"=>"profile",
                    "column"=> "profile_id",
                    "order"=> "desc"
                ],
                "limit_offset"=>[
                    "limit"=>2,
                    "offset"=> 0
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_id, profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex FROM profile WHERE profile_nationality = \'Filipino\' ORDER BY profile.profile_id DESC LIMIT 2 OFFSET 0',
            'data' => [
              0 => [
                'profile_id' => 41,
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Alex',
                'profile_middleName' => 'Nuttle',
                'profile_lastName' => 'Ingram',
                'profile_sex' => 'Female',
              ],
              1 => [
                'profile_id' => 35,
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Steve',
                'profile_middleName' => 'Cataldi',
                'profile_lastName' => 'Ory',
                'profile_sex' => 'Male',
              ],
            ]
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect() {

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                        "table"=>"passport_detail",
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
                        "table"=> "manifest",
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
                "join"=>[
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
                "limit_offset"=>[
                    "limit"=>2,
                    "offset"=>0
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_id, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, passport_detail.pd_passportNumber, passport_detail.pd_country, manifest.manifest_airlineNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id LIMIT 2 OFFSET 0',
            'data' => [
              0 => [
                'profile_id' => 1,
                'profile_firstName' => 'Roger',
                'profile_middleName' => 'Yang',
                'profile_lastName' => 'Fletcher',
                'pd_passportNumber' => 'skJbENpKOo1',
                'pd_country' => 'Russia',
                'manifest_airlineNumber' => 'qYxKHCUytS1',
                'manifest_airlineCode' => 'n1f7bDP52k',
                'manifest_flightNo' => 'ifvgw3aDAb',
              ],
              1 => [
                'profile_id' => 2,
                'profile_firstName' => 'Ike',
                'profile_middleName' => 'Lulloff',
                'profile_lastName' => 'Orwig',
                'pd_passportNumber' => 'y14JRO2kNu2',
                'pd_country' => 'China',
                'manifest_airlineNumber' => 'TkNWWUwdaE2',
                'manifest_airlineCode' => 'KPqojxBFJj',
                'manifest_flightNo' => 'IdAUjaNdbY',
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect__with_multipleWhereCondition() {
    
        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                        "table"=>"passport_detail",
                        "column"=> [
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_passportNumber",
                            ]
                        ]
                    ],
                    [
                        "table"=> "manifest",
                        "column"=> [
                          
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
                "join"=>[
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
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_sex = \'Male\' AND profile_nationality = \'Filipino\'',
            'data' => [
              0 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Nathan',
                'profile_middleName' => 'Pettigrew',
                'profile_lastName' => 'Pak',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'WdpUvd9djr5',
                'manifest_airlineCode' => 'u2SZuSL53v',
                'manifest_flightNo' => 'kqdXipdNJQ',
              ],
              1 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Edward',
                'profile_middleName' => 'Soulis',
                'profile_lastName' => 'Ziegler',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'q0W6KPNPrA10',
                'manifest_airlineCode' => 'EEVHENdjya',
                'manifest_flightNo' => 'gGCAPgqdkz',
              ],
              2 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Frank',
                'profile_middleName' => 'Moody',
                'profile_lastName' => 'Sawyer',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'rYZALzAacK28',
                'manifest_airlineCode' => 'wdi9U5nVSg',
                'manifest_flightNo' => 'AOfQoEyAfk',
              ],
              3 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Steve',
                'profile_middleName' => 'Cataldi',
                'profile_lastName' => 'Ory',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'YvV1Lai0wY35',
                'manifest_airlineCode' => 'jLtZOeBvv6',
                'manifest_flightNo' => 'dJ1z9OWO9W',
              ],
            ],
        ];
        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_aggregateFunction_and_groupBy(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                "group_by"=>[

                    "column_name"=>"profile_nationality"
                ],
                "limit_offset"=>[
                    "limit"=>2,
                    "offset"=>0
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json =[
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, count(profile.profile_id) FROM profile GROUP BY profile_nationality LIMIT 2 OFFSET 0',
            'data' => [
              0 => [
                'profile_nationality' => 'Chinese',
                'count(profile.profile_id)' => 7,
              ],
              1 => [
                'profile_nationality' => 'Filipino',
                'count(profile.profile_id)' => 11,
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_singleTableSelect_with_aggregateFunction_groupBy_having(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
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
                "group_by"=>[
    
                    "column_name"=>"profile_nationality"
                ],
                "having"=>[
                    "agg_function"=>"count",
                    "column_name"=>"profile_id",
                    "operator"=>">",
                    "value"=>"10"
                ],
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, count(profile.profile_id) FROM profile GROUP BY profile_nationality HAVING count(profile_id) > \'10\'',
            'data' => [
              0 => [
                'profile_nationality' => 'Filipino',
                'count(profile.profile_id)' => 11,
              ],
              1 => [
                'profile_nationality' => 'Japanese',
                'count(profile.profile_id)' => 14,
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect_with_aggregateFunction_and_groupBy(){

        $request = [
            "command"=>"read",
            "query_data"=>[
                "select"=>[
                    [
                        "table"=>"profile",
                        "column"=>[
                                [
                                    "agg_function"=>"count",
                                    "column_name"=>"profile_id"
                                ],
                            ]
                    ],
                    [
                        "table"=>"passport_detail",
                        "column"=>[
                            [
                                "agg_function"=>"",
                                "column_name"=>"pd_country"
                            ],
                        ]
                    ]
                ],
                "join"=>[
                        [
                            'join_from' => 'profile',
                            'join_to' => 'passport_detail',
                            'join_name' => 'join',
                            'join_key' => 'profile_id',
                        ]
                  ],
                "group_by"=>[
                    "column_name"=>"pd_country"
                ],
                "limit_offset"=>[
                    "limit"=>2,
                    "offset"=>0
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT count(profile.profile_id), passport_detail.pd_country FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id GROUP BY pd_country LIMIT 2 OFFSET 0',
            'data' => [
              0 => [
                'count(profile.profile_id)' => 8,
                'pd_country' => 'China',
              ],
              1 => [
                'count(profile.profile_id)' => 9,
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
              'select' => [
                    [
                        'table' => 'profile',
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
                        'table' => 'passport_detail',
                        'column' => [
                            0 => [
                            'agg_function' => '',
                            'column_name' => 'pd_passportNumber',
                            ]
                        ],
                    ],
                    [
                        'table' => 'manifest',
                        'column' => [
                            [
                            'agg_function' => '',
                            'column_name' => 'manifest_flightNo',
                            ],
                    ],
                ],
              ],
              'join' => [
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
                    ]
                ],
                'logical_connector' => [
                    'and'
                ],
              ],
            ],
          ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_sex = \'Male\' AND profile_nationality IN (\'Filipino\' , \'Chinese\')',
            'data' => [
              0 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Nathan',
                'profile_middleName' => 'Pettigrew',
                'profile_lastName' => 'Pak',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'WdpUvd9djr5',
                'manifest_flightNo' => 'kqdXipdNJQ',
              ],
              1 => [
                'profile_nationality' => 'Chinese',
                'profile_firstName' => 'Frank',
                'profile_middleName' => 'Schlicht',
                'profile_lastName' => 'Aikin',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'x7WzGt2FF36',
                'manifest_flightNo' => 't6eVHbjeZS',
              ],
              2 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Edward',
                'profile_middleName' => 'Soulis',
                'profile_lastName' => 'Ziegler',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'q0W6KPNPrA10',
                'manifest_flightNo' => 'gGCAPgqdkz',
              ],
              3 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Frank',
                'profile_middleName' => 'Moody',
                'profile_lastName' => 'Sawyer',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'rYZALzAacK28',
                'manifest_flightNo' => 'AOfQoEyAfk',
              ],
              4 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Steve',
                'profile_middleName' => 'Cataldi',
                'profile_lastName' => 'Ory',
                'profile_sex' => 'Male',
                'pd_passportNumber' => 'YvV1Lai0wY35',
                'manifest_flightNo' => 'dJ1z9OWO9W',
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect_with_singleWhereCondition_of_multipleParameter(){

        $request = [
            'command' => 'read',
            'query_data' => [
              'select' => [
                    [
                        'table' => 'profile',
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
                        'table' => 'passport_detail',
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
                        'table' => 'manifest',
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
              'join' => [
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
              'limit_offset' => [
                    "limit"=>2,
                    "offset"=>0

              ],
            ],
          ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, passport_detail.pd_country, manifest.manifest_airlineNumber, manifest.manifest_airlineCode, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_nationality IN (\'Filipino\' , \'Chinese\') LIMIT 2 OFFSET 0',
            'data' => [
              0 => [
                'profile_nationality' => 'Filipino',
                'profile_firstName' => 'Ike',
                'profile_middleName' => 'Lulloff',
                'profile_lastName' => 'Orwig',
                'profile_sex' => 'Female',
                'pd_passportNumber' => 'y14JRO2kNu2',
                'pd_country' => 'China',
                'manifest_airlineNumber' => 'TkNWWUwdaE2',
                'manifest_airlineCode' => 'KPqojxBFJj',
                'manifest_flightNo' => 'IdAUjaNdbY',
              ],
              1 => [
                'profile_nationality' => 'Chinese',
                'profile_firstName' => 'Steve',
                'profile_middleName' => 'Ashwoon',
                'profile_lastName' => 'Roberts',
                'profile_sex' => 'Female',
                'pd_passportNumber' => 'nXI4q4EchO4',
                'pd_country' => 'China',
                'manifest_airlineNumber' => 'F0HMMBqA424',
                'manifest_airlineCode' => 'YR3X8sI3dc',
                'manifest_flightNo' => 'ZHw6I62o2v',
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }

    public function test_multipleTableSelect_with_whereCondition_containingSubQuery(){

        $request = [
            'command' => 'read',
            'query_data' => [
              'select' => [
                    [
                        'table' => 'profile',
                        'column' => [
                            [
                                'agg_function' => '',
                                'column_name' => 'profile_age',
                            ],
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
                            ]
                        ],
                    ],
                    [
                        'table' => 'passport_detail',
                        'column' => [
                            [
                                'agg_function' => '',
                                'column_name' => 'pd_passportNumber',
                            ]
                        ],
                    ],
                    [
                        'table' => 'manifest',
                        'column' => [
                            [
                                'agg_function' => '',
                                'column_name' => 'manifest_flightNo',
                            ]
                        ],
                    ]
                ],
                'join' => [
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
                            'column_name' => 'profile_age',
                            'operator' => '>',
                            'value' => [
                                'sub_query_data' => [
                                    'select' => [
                                        [
                                            'table' => 'profile',
                                            'column' => [
                                                [
                                                    'agg_function' => '',
                                                    'column_name' => 'profile_age'
                                                ]
                                            ]
                                        ],
                                    ],
                                    'join'=>[],
                                    'where' => [
                                        'parameter' => [
                                            [
                                                'column_name' => 'profile_firstName',
                                                'operator' => '=',
                                                'value'=>'Roger'
                                            ],
                                            [
                                                'column_name' => 'profile_lastName',
                                                'operator' => '=',
                                                'value'=>'Fletcher'
                                            ]
                                        ],
                                        'logical_connector' => ['and']
                                    ],
                                    'group_by' => [],
                                    'having' => [],
                                    'order_by' => [],
                                    'limit_offset' => []
                                ]
                            ]
                        ]
                    ],
                    'logical_connector' => []
                ]
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);

        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_age, profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex, passport_detail.pd_passportNumber, manifest.manifest_flightNo FROM profile AS profile JOIN passport_detail AS passport_detail ON profile.profile_id = passport_detail.profile_id JOIN manifest AS manifest ON profile.profile_id = manifest.profile_id WHERE profile_age > (SELECT profile.profile_age FROM profile WHERE profile_firstName = \'Roger\' AND profile_lastName = \'Fletcher\')',
            'data' => [
              0 => [
                'profile_age' => 60,
                'profile_nationality' => 'Japanese',
                'profile_firstName' => 'Larry',
                'profile_middleName' => 'Uddin',
                'profile_lastName' => 'LePage',
                'profile_sex' => 'Female',
                'pd_passportNumber' => 'NwrB2SAYbV50',
                'manifest_flightNo' => 'Q4my7wtHMx',
              ],
              1 => [
                'profile_age' => 60,
                'profile_nationality' => 'Korean',
                'profile_firstName' => 'Walter',
                'profile_middleName' => 'Johnsen',
                'profile_lastName' => 'Ventotla',
                'profile_sex' => 'Male',
                'pd_passportNumber' => '56gKjZ63m923',
                'manifest_flightNo' => 'WCzcwsrXRc',
              ],
            ],
        ];

        $response->seeJsonEquals($expected_json);
    }


    public function test_singleTableSelect_with_whereCondition_containingSubQuery(){

        $request = [
            'command' => 'read',
            'query_data' => [
                'select' => [
                    [
                        'table' => 'profile',
                        'column' => [
                            [
                                'agg_function' => '',
                                'column_name' => 'profile_age',
                            ],
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
                    ]
                ],
                'where' => [
                    'parameter' => [
                        [
                            'column_name' => 'profile_age',
                            'operator' => '>',
                            'value' => [
                                'sub_query_data' => [
                                    'select' => [
                                        [
                                            'table' => 'profile',
                                            'column' => [
                                                0 => [
                                                'agg_function' => '',
                                                'column_name' => 'profile_age',
                                                ],
                                            ],
                                        ],
                                    ],
                                    'join' => [],
                                    'where' => [
                                        'parameter' => [
                                            [
                                                'column_name' => 'profile_firstName',
                                                'operator' => '=',
                                                'value' => 'Roger',
                                            ],
                                            [
                                                'column_name' => 'profile_lastName',
                                                'operator' => '=',
                                                'value' => 'Fletcher',
                                            ],
                                        ],
                                        'logical_connector' => [
                                            'and',
                                        ],
                                    ],
                                    'group_by' => [],
                                    'having' => [],
                                    'order_by' => [],
                                    'limit_offset' => [],
                                ],
                            ],
                        ],
                    ],
                    'logical_connector' => [],
                ],
                'order_by' => [
                    'table' => 'profile',
                    'column' => 'profile_age',
                    'order' => 'desc',
                ],
            ],
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        $response->seeStatusCode(self::HTTP_CREATED);

        $expected_json = [
            'status' => 'success',
            'query' => 'SELECT profile.profile_age, profile.profile_nationality, profile.profile_firstName, profile.profile_middleName, profile.profile_lastName, profile.profile_sex FROM profile WHERE profile_age > (SELECT profile.profile_age FROM profile WHERE profile_firstName = \'Roger\' AND profile_lastName = \'Fletcher\') ORDER BY profile.profile_age DESC',
            'data' => [
              0 => [
                'profile_age' => 60,
                'profile_nationality' => 'Korean',
                'profile_firstName' => 'Walter',
                'profile_middleName' => 'Johnsen',
                'profile_lastName' => 'Ventotla',
                'profile_sex' => 'Male',
              ],
              1 => [
                'profile_age' => 60,
                'profile_nationality' => 'Japanese',
                'profile_firstName' => 'Larry',
                'profile_middleName' => 'Uddin',
                'profile_lastName' => 'LePage',
                'profile_sex' => 'Female',
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
                "select"=>[
                    [
                        "table"=>"profile",
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
            ]
        ];

        $response = $this->json(self::HTTP_METHOD_POST, self::HTTP_URL, $request, [
                        'Accept'=>'application/json',
                        'Content-Type'=>'application/json',
                        'Authorization'=>'Bearer '. self::HTTP_TOKEN
                    ]);
        $response->seeStatusCode(self::HTTP_SERVER_ERROR);

        $expected_json = [
            'status' => 'error',
            'query' => 'SELECT profile.proadwadawfile_natioadwadanality, profile.profiawdawdawle_firadwadawdstName, profile.profiawdawdawle_miadwadawddleName, profile.profadawdile_lastawdawdawName, profile.profiladawdawdwae_sex FROM profile',
            'data' => 'Sql Query is not valid',
        ];

        $response->seeJsonEquals($expected_json);
        
    }
}
