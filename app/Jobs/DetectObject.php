<?php

namespace App\Jobs;

use App\Services\Queues\QueueGet;
use App\Services\Queues\QueueSet;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DetectObject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $queue_id = 0;

    public function __construct(int $queue_id)
    {
        $this->queue_id = $queue_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $queue_id = $this->queue_id;
        echo "Run queue: $queue_id" . PHP_EOL;
        $queue = QueueGet::getById($queue_id);
        try {
            if (empty($queue)) {
                echo "Error: Not Found queue: {$queue->status}" . PHP_EOL;
                throw new \Exception('Run job DetectObject fail: not found queue!');
            }
            if ($queue->status != 0) {
                echo "Error: Job status: {$queue->status}" . PHP_EOL;
                throw new \Exception("Run job DetectObject fail: Job status: {$queue_id}:  {$queue->status}!");
            }
            $queue_data = $queue->data;
            $time_start = microtime(true);
            $params = [
                [
                    'name' => 'image',
                    'contents' => Storage::disk('local')->get("public/{$queue_data['path']}"),
                    'filename' => 'image.jpg'
                ]
            ];
            QueueSet::update($this->queue_id,[
                'status' => 1
            ]);
            $client = new Client();
            $response = $client->post(config('detect.detect_object_url') . '/detect-object', [
                'multipart' => $params
            ]);
            $process_time = round(microtime(true) - $time_start, 3);
            if ($response->getStatusCode() != 200)
                throw new \Exception("Detect object error!");
            $path = 'temp/' . date("H") . "/detect-object/" . time() . "_" . Str::random(10) . "-out.jpg";
            Storage::disk('local')->put('public/' . $path, $response->getBody()->getContents());
            $queue->value = [
                'type' => 'image',
                'path' => $path,
            ];
            $queue->process_time = $process_time;
            $queue->status = 2;
            $queue->save();
            echo "Value: " . Storage::disk('local')->url($path) . PHP_EOL;
        } catch (\Exception $e) {
            $queue->status = 3;
            $queue->error = $e->getMessage();
            $queue->save();
            echo "Error: Process return error: {$e->getMessage()}" . PHP_EOL;
        }
    }
}
