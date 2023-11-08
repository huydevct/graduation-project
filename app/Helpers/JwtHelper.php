<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

class JwtHelper
{
    static function createTokenDevice(array $data, float $expire = 1): string
    {
        $time_now = time();
        $payload = [
            "iat" => $time_now,
            "data" => $data,
        ];
        if ($expire != -1) {
            $expire = 3600 * $expire;// Tính theo giờ
            $payload['exp'] = $time_now + $expire;
        }
        $jwt = JWT::encode($payload, config('auth.jwt.device_secret'), 'HS256');
        return $jwt;
    }


    static function createTokenUser(array $data, float $expire = 1): string
    {
        $time_now = time();
        $payload = [
            "iat" => $time_now,
            "data" => $data,
        ];
        if ($expire != -1) {
            $expire = 3600 * $expire;// Tính theo giờ
            $payload['exp'] = $time_now + $expire;
        }
        $jwt = JWT::encode($payload, config('auth.jwt.user_secret'), 'HS256');
        return $jwt;
    }
}
