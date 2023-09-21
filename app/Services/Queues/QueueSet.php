<?php

namespace App\Services\Queues;

use App\Models\Queue;

class QueueSet
{
    static function create(array $data): Queue
    {
        $queue_ai = new Queue();
        foreach ($data as $key => $item) {
            $queue_ai->{$key} = $item;
        }
        $queue_ai->save();
        return $queue_ai;
    }

    static function update($id,array $data){
        Queue::where('id',$id)->update($data);
    }
}
