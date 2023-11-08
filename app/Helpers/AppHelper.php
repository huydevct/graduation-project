<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Imagick;
use ImagickDraw;
use ImagickPixel;

class AppHelper
{

    static function getFullImagePathFromFileName(string $file_name)
    {
        $time_file = explode("_", $file_name);
        if (!isset($time_file[1])) return false;
        $date_file = date("Y/m/d", (int)$time_file[0]);
        $path = "upload/images/full/$date_file/$file_name";
        return $path;
    }

    static function getImagePathFromFilename(string $file_name, bool $include_url_end_point = false)
    {

        $time_file = explode("_", $file_name);
        if (!isset($time_file[1])) return false;
        $date_file = date("Y/m/d", (int)$time_file[0]);
        $types = ['full', 'preview', 'small', 'extra_small'];
        $paths = array_map(function ($item) use ($date_file, $file_name) {
            return "upload/images/$item/$date_file/$file_name";
        }, $types);

        if ($include_url_end_point == true) {
            return [
                'full' => Storage::url($paths[0]),
                'preview' => Storage::url($paths[1]),
                'small' => Storage::url($paths[2]),
                'extra_small' => Storage::url($paths[3])
            ];
        }

        return [
            'full' => $paths[0],
            'preview' => $paths[1],
            'small' => $paths[2],
            'extra_small' => $paths[3]
        ];

    }

    static function encodeOpenSsl(string $string, $key = null): string
    {
        $ivSize = openssl_cipher_iv_length('AES-256-CBC');
        $iv = openssl_random_pseudo_bytes($ivSize);
        $key_verify = config('auth.openssl.device_secret');
        if (!empty($key)) {
            $key_verify = $key;
        }
        $encrypted = openssl_encrypt($string, 'AES-256-CBC', $key_verify, OPENSSL_RAW_DATA, $iv);
        $encoded = base64_encode($iv . $encrypted);
        return $encoded;
    }

    static function decodeOpenSsl(string $string, $key = null): string
    {
        $decoded = base64_decode($string);
        $ivSize = openssl_cipher_iv_length('AES-256-CBC');
        $iv = substr($decoded, 0, $ivSize);
        $encrypted = substr($decoded, $ivSize);
        $key_verify = config('auth.openssl.device_secret');
        if (!empty($key)) {
            $key_verify = $key;
        }
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key_verify, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }

    static function AuthApi()
    {
        return AuthApi::getInstance();
    }

    static function createPathFileStorageTmp($type, $type_file, $timestamp = 0)
    {
        if ($timestamp == 0) $timestamp = time();
        return 'temp/' . date("H", $timestamp) . "/$type/" . $timestamp . "_" . Str::random(10) . ".$type_file";
    }

    static function getUriFileTmp($tmp)
    {
        return stream_get_meta_data($tmp)['uri'];
    }

    static function getFileTmp($tmp)
    {
        return File::get(self::getUriFileTmp($tmp));
    }

    static function deleteFileTmp($tmp)
    {
        try {
            fclose($tmp);
        } catch (\Exception $e) {
            Log::error("Delete file tmp error!");
        }
    }

    static function orientateImage($path, $type_file = 'png')
    {
        $inter_image = Image::make($path)->orientate();
        return $inter_image->encode($type_file, 100);
    }

    static function renderMask(int $width, int $height, array $position_logo, $disk_save): string
    {
        $im = new Imagick();
        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel('white'));
        foreach ($position_logo as $position) {
            $draw->rectangle($position[0], $position[1], $position[2], $position[3]);
        }
        $im->newImage($width - 2, $height - 2, new ImagickPixel('transparent')); // transparent

        $im->drawImage($draw);

        $im->borderImage(new ImagickPixel('transparent'), 1, 1);

        $im->setImageFormat('png');
        $path = "temp/" . date("H") . "/remove_object/" . time() . "_" . Str::random(10) . ".png";
        Storage::disk($disk_save)->put($path, $im);
        return $path;
    }

    static function convertPngToJpeg($source_image_path, $output_image_path, $quality = 70)
    {
        // Load the PNG image.
        $image = imagecreatefrompng($source_image_path);

        // Save the image as a JPEG.
        imagejpeg($image, $output_image_path, $quality);

        // Destroy the image resource.
        imagedestroy($image);
    }

    static function printLogQueue(string $string)
    {
        echo $string . PHP_EOL;
    }

    static function googleGetInfo($access_token)
    {
        try {
            $user_google_info = file_get_contents("https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $access_token);
            $user_google_info = json_decode($user_google_info, 1);
            if (isset($user_google_info['id'])) {
                $user_google_info['status_login'] = true;
            } else {
                $user_google_info['status_login'] = false;
            }
            return $user_google_info;
        } catch (\Exception $e) {
            Log::error("Google verify error: {$e->getMessage()}");
            return null;
        }
    }

    static function generateUuid() {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    static function getTypeConvert(string $type)
    {
        $type_return = 'jpg';
        $types = [
            'jpeg' => 'jpg',
            'jpg' => 'jpg',
            'png' => 'png',
            'webp' => 'png'
        ];
        if (isset($types[$type])) $type_return = $type;
        return $type_return;
    }
}
