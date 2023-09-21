<?php

namespace App\Http\Controllers;

use App\Services\Queues\QueueGet;

class QueueController extends Controller
{
    function index()
    {
        $queues_ai = QueueGet::getByApi();
        return $this->response($queues_ai);
    }

    function show($id)
    {
        $queue_ai = QueueGet::getIdByApi($id);
        return $this->response($queue_ai);
    }
}
