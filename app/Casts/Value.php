<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Value implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!empty($value)) {
            $value = json_decode($value, 1);
            if ($value['type'] == "video plate"){
                $value['url'] = Storage::disk('minio')->url($value['path']);
            }else{
                $value['url'] = Storage::disk('public')->url($value['path']);
            }
        }
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if(!empty($value)&&is_array($value)){
            $value = json_encode($value);
        }
        return $value;
    }
}
