<?php

namespace App\Controllers;

use Core\Controller;
use Core\Request;

class FeedbackController extends Controller
{
    function defineEndpoints(): void
    {
        $this->registerEndpoint('POST', '/api/feedback', 'feedback');
    }

    function feedback(Request $req): void
    {
        $price = (int)$req->body['price'];
        $name = $req->body['name'];
        $email = $req->body['email'];
        $phone = $req->body['phone'];
        $was30sec = $req->body['was30sec'];

        if(empty($email) || empty($phone) || empty($name)) {
            $this->error("Missing or empty field", 400);
            return;
        }
        

        $headers = [
            'Authorization: Bearer ' . LONG_TERM_TOKEN,
            'Content-Type: application/json'
        ];
       

        $body = [
            [
                'price' => $price,
                'custom_fields_values' => [
                    [
                        'field_id' => 2217255,
                        'values' => [
                            ['value' => $was30sec]
                        ]
                    ],
                    [
                        'field_id' => 2217247,
                        'values' => [
                            ['value' => $name]
                        ]
                    ],
                    [
                        'field_id' => 2217249,
                        'values' => [
                            ['value' => $email]
                        ]
                    ],
                    [
                        'field_id' => 2217251,
                        'values' => [
                            ['value' => $phone]
                        ]
                    ],
                    [
                        'field_id' => 2217253,
                        'values' => [
                            ['value' => $price]
                        ]
                    ]
                ]
            ]
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, CLIENT_URL . '/leads');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if($code >= 400) {
            $this->error("Internal server error", 500);
            return;
        }

        $this->ok($result);
    }
}