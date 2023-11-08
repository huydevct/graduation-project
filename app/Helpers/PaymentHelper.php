<?php

namespace App\Helpers;

use App\Jobs\SendMessageToSlack;
use Illuminate\Support\Facades\Log;
use Braintree\Gateway;
use GuzzleHttp\Client;

class PaymentHelper
{
    static function PayPal()
    {
        return new Gateway([
            'accessToken' => config('payment.methods.paypal.access_token'),
        ]);
    }

    static function generateAccessToken(){
        try {
            $client = new Client();
            $res = $client->post(config("payment.methods.paypal.endpoint") . "/v1/oauth2/token", [
                "form_params" => [
                    "grant_type" => "client_credentials",
//                    "ignoreCache" => true,
//                    "return_authn_schemes" => true,
//                    "return_client_metadata" => true,
//                    "return_unconsented_scopes" => true,
                ],
                "headers" => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'PayPal-Request-Id' => AppHelper::generateUuid(),
                ],
                'auth' => [
                    config('payment.methods.paypal.client_id'),
                    config('payment.methods.paypal.secret')
                ],
            ]);
            $body = $res->getBody()->getContents();
            $body = json_decode($body, 1);
            $access_token = $body['access_token'];
            return $access_token;
        } catch (\Exception $exception) {
//            dispatch(new SendMessageToSlack(['paypal error' => $exception->getMessage(), 'body' => [
//                "grant_type" => "client_credentials",
//                "ignoreCache" => true,
//                "return_authn_schemes" => true,
//                "return_client_metadata" => true,
//                "return_unconsented_scopes" => true,
//            ]],'error'));
            return false;
        }
    }

    static function verifyPaypalWebhookResponse($params, $headers, $access_token)
    {
        $request_body = [
            "webhook_id" => config('payment.webhook_id'),
            "transmission_id" => $headers['paypal-transmission-id'][0],
            "transmission_time" => $headers['paypal-transmission-time'][0],
            "cert_url" => $headers['paypal-cert-url'][0],
            "auth_algo" => $headers['paypal-auth-algo'][0],
            "transmission_sig" => $headers['paypal-transmission-sig'][0],
            "webhook_event" => $params,
        ];

        // debug
//        Log::info('request body paypal', $request_body);
//        Log::info('request header paypal', [
//            'Content-Type' => 'application/json',
//            'PayPal-Request-Id' => AppHelper::generateUuid(),
//            'Authorization' => 'Bearer '.config('payment.methods.paypal.access_token_veri')
//        ]);
//        Log::info('request', [
//            "json" => $request_body,
//            "headers" => [
//                'Content-Type' => 'application/json',
//                'PayPal-Request-Id' => AppHelper::generateUuid(),
//                'Authorization' => 'Bearer '.config('payment.methods.paypal.access_token_veri')
//            ],
//        ]);

        try {
            $client = new Client();
            $res = $client->post(config("payment.methods.paypal.endpoint") . "/v1/notifications/verify-webhook-signature", [
                "json" => $request_body,
                "headers" => [
                    'Content-Type' => 'application/json',
                    'PayPal-Request-Id' => AppHelper::generateUuid(),
                    'Authorization' => 'Bearer ' . $access_token
                ],
            ]);
        } catch (\Exception $exception) {
            dispatch(new SendMessageToSlack(['paypal error' => $exception->getMessage(), 'body' => $request_body],'error'));
            return false;
        }

        $body = $res->getBody()->getContents();
        $body = json_decode($body, 1);
        if (empty($body['verification_status']) || $body['verification_status'] != 'SUCCESS') {
            return false;
        }

        return true;
    }

    static function createOrderPayPal(float $amount, string $description)
    {
        $client = new Client();
        $res = $client->post(config("payment.methods.paypal.endpoint") . "/v2/checkout/orders", [
            "headers" => [
                'Content-Type' => 'application/json',
                'PayPal-Request-Id' => AppHelper::generateUuid()
//               'Authorization' => 'Basic '.config('payment.methods.paypal.access_token')
            ],
            'auth' => [
                config('payment.methods.paypal.client_id'),
                config('payment.methods.paypal.secret')
            ],
            'json' => [
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => config('payment.methods.paypal.return_url'),
                    "cancel_url" => config('payment.methods.paypal.cancel_url'),
                    "locale" => "en-US",
                    "landing_page" => "BILLING",
//    "shipping_preference": "SET_PROVIDED_ADDRESS",
                    "user_action" => "PAY_NOW",
                    "payment_method" => [
                        "payee_preferred" => "IMMEDIATE_PAYMENT_REQUIRED",
                        "standard_entry_class_code" => "TEL"
                    ],
                ],
                "purchase_units" => [
                    [
                        "description" => $description,
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $amount
                        ],
                        "payee" => config("payment.methods.paypal.info_business")
                    ]
                ]
            ]
        ]);
        $body = $res->getBody()->getContents();
        $body = json_decode($body, 1);
        return $body;
    }

    static function checkOrderPaypal($order_id)
    {
        $client = new Client();
        $res = $client->post(config("payment.methods.paypal.endpoint") . "/v2/checkout/orders/$order_id/authorize", [
            "headers" => [
                'Content-Type' => 'application/json',
                'PayPal-Request-Id' => AppHelper::generateUuid(),
                "Prefer" => "return=representation"
            ],
            'auth' => [
                config('payment.methods.paypal.client_id'),
                config('payment.methods.paypal.secret')
            ]
        ]);
        $body = $res->getBody()->getContents();
        $body = json_decode($body, 1);
        return $body;
    }

    static function createHookPaypal(string $url, $event_name = "*")
    {

        $client = new Client();
        $res = $client->post(config("payment.methods.paypal.endpoint") . '/v1/notifications/webhooks', [
            'json' => [
                'url' => $url,
                'event_types' => [
                    "name" => $event_name
                ]
            ],
            "headers" => [
                'Content-Type' => 'application/json',
                'PayPal-Request-Id' => AppHelper::generateUuid()
            ],
            'auth' => [
                config('payment.methods.paypal.client_id'),
                config('payment.methods.paypal.secret')
            ]
        ]);

        $body = $res->getBody()->getContents();
        return $body;
    }
}
