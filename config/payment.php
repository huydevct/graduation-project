<?php
return [
    'methods' => [
        'paypal' => [
            'type' => 1,
//            'endpoint' => 'https://api-m.sandbox.paypal.com',
            'endpoint' => 'https://api-m.paypal.com',
            'account' => env('PAYPAL_ACCOUNT'),
            'access_token' => env('PAYPAL_ACCESS_TOKEN'),
            'access_token_veri' => env('PAYPAL_ACCESS_TOKEN_VERI'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
            "return_url" => "https://vpljail.ink",
            "cancel_url" => "https://vpljail.ink",
        ],
    ],
    'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
    'event_types' => [
        'success' => 'CHECKOUT.ORDER.APPROVED',
        'process' => 'CHECKOUT.ORDER.PROCESSED',
    ],
    'info_business' => [
        "email_address" => "sb-jgbqn12111212@business.example.com",
//        "merchant_id" => "65MXV4T2ZV6KY",
        "display_data" => [
            "business_email" => "support@example.com",
            "business_phone" => [
                "country_code" => "1",
                "national_number" => "18882211161",
                "extension_number" => "9"
            ],
            "brand_name" => "Example Business"
        ]
    ],
    'packages' => [
        'month' => [
            'price' => 5
        ],
        'year' => [
            'price' => 50
        ],
        'lifetime' => [
            'price' => 100
        ]
    ]
];
