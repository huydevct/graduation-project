<?php

namespace App\Services\LicensePlate;

use App\Models\License_Plate;

class LicensePlateSet
{
    static function create(array $data): License_Plate
    {
        $lp = new License_Plate();
        foreach ($data as $key => $value){
            $lp->{$key} = $value;
        }
        $lp->save();
        return $lp;
    }
}
