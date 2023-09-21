<?php

namespace App\Services\Queues;

use App\Models\Queue;
use Ngocnm\LaravelHelpers\Helper;

class QueueGet
{
    static function getByApi(){
        $queues_ai = Queue::baseQueryBuilder(Queue::class);
        return $queues_ai->simplePaginate(Helper::BaseApiRequest()->getLimit());
    }

    static function getIdByApi($id){
        $queues_ai = Queue::baseQueryBuilder(Queue::class);
        $queues_ai->where('id',$id);
        return $queues_ai->first();
    }

    static function getById(int $id, $fields = null): Queue
    {
        return Queue::find($id);
    }
}
