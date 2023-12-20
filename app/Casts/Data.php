<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Data implements CastsAttributes
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
            if (!empty($value['path']) && !is_array($value['path'])) {
                $value['url'] = Storage::disk('public')->url($value['path']);
            }
            if (!empty($value['path']) && is_array($value['path'])){
                $array_url = [];
                foreach ($value['path'] as $path){
                    $array_url[] = Storage::disk('public')->url($path);
                }
                $value['url'] = $array_url;
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
