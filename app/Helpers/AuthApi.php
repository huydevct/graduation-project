<?php

namespace App\Helpers;

use App\Models\Device;
use App\Models\User;
use App\Services\Devices\DeviceSet;
use AWS\CRT\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ngocnm\LaravelHelpers\StringHelper;

class AuthApi
{
    use SingletonTrait;

    private $user_id = 0;
    private $device_id = 0;

    private $verify = false;

    private $client_id = '';

    private $client_type = 0;

    private $name = '';

    private $device = null;

    private $user = null;

    private function setData($data): void
    {
        if (!empty($data->data->user_id)) $this->user_id = $data->data->user_id;
        if (!empty($data->data->device_id)) $this->device_id = $data->data->device_id;
        if(!empty($data->data->client_id)) $this->client_id = $data->data->client_id;
        if(!empty($data->data->name)) $this->name = $data->data->name;
        $this->client_type = $data->data->type;
        $this->verify = true;
    }

    public function verifyToken(string $token)
    {
        try {
            // Check token
            $payload = explode(".", $token);
            if (!isset($payload[1])) throw new \Exception("Not found payload token");
            $payload = json_decode(base64_decode($payload[1]), true);
            if (!isset($payload['data']) || (!isset($payload['data']['user_id']) && !isset($payload['data']['device_id']))) throw new \Exception("Not found payload data token");
            if (isset($payload['data']['user_id']) && $payload['data']['user_id'] != 0) {
                $secret = config('auth.jwt.user_secret');
            } else if (isset($payload['data']['device_id']) && $payload['data']['device_id'] != 0) {
                $secret = config('auth.jwt.device_secret');
            } else if (isset($payload['data']['client_id'])) {
                $secret = config('auth.jwt.device_secret');
            } else {
                throw new \Exception("Payload token error!");
            }
            // verify token
            $token_info = JWT::decode($token, new Key($secret, 'HS256'));
            $this->setData($token_info);
            return true;
        } catch (\Exception $e) {
//            dd($e->getMessage());
            \Illuminate\Support\Facades\Log::error("JWT auth error: {$e->getMessage()}");
            return false;
        }
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getDeviceId()
    {
        if ($this->device_id == 0 && !empty($this->client_id)) {
            $data = [
                'device_id' => StringHelper::filter($this->client_id),
                'name' => StringHelper::filter($this->name),
                'type' => $this->client_type,
                'active' => 1
            ];
            $device = DeviceSet::createOrFind($data);
            $this->device_id = $device->id;
        }
        return $this->device_id;
    }

    public function getUser()
    {
        if ($this->user_id != 0 && empty($this->user)) {
            $this->user = User::select('id', 'name', 'email', 'login_id', 'picture', 'locale')->where('id', $this->user_id)->first();
        }
        return $this->user;
    }

    public function getDevice()
    {
        if ($this->device_id != 0 && empty($this->device)) {
            $this->device = Device::where('id', $this->device_id)->first();
        }
        return $this->device;
    }

    public function getTypeClient()
    {
        return $this->client_type;
    }

    public function getNameClient()
    {
        return $this->name;
    }

    public function getClientId()
    {
        return $this->client_id;
    }
}
