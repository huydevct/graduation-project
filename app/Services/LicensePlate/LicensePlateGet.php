<?php

namespace App\Services\LicensePlate;

use App\Models\License_Plate;

class LicensePlateGet
{
    static function getByQueueId($id){
        return License_Plate::where('queue_id', $id)->first();
    }
}
