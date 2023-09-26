<?php

namespace App\Jobs;

use App\Services\Queues\QueueGet;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DetectLP implements ShouldQueue
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
                throw new \Exception('Run job DetectLP fail: not found queue!');
            }
            if ($queue->status != 0) {
                echo "Error: Job status: {$queue->status}" . PHP_EOL;
                throw new \Exception("Run job DetectLP fail: Job status: {$queue_id}:  {$queue->status}!");
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
            if (!empty($queue_data['model'])) {
                $params[] = [
                    'name' => 'model',
                    'contents' => $queue_data['model'],
                ];
            }
            $client = new Client();
            $response = $client->post(config('detect.detect_lp_url') . '/detect-lp', [
                'multipart' => $params
            ]);
            $process_time = round(microtime(true) - $time_start, 3);
            if ($response->getStatusCode() != 200)
                throw new \Exception("Detect LP image error!");
            $path = 'temp/' . date("H") . "/detect-lp/" . time() . "_" . Str::random(10) . "-out.jpg";
            $responses = $response->getBody()->getContents();
            $response = json_decode($responses);
            $file_out = file_get_contents(base_path("License-Plate-Recognition/" . $response->file_path_out));
            Storage::disk('local')->put('public/' . $path, $file_out);
            if (File::exists(base_path("License-Plate-Recognition/" . $response->file_path_out))) {
                unlink(base_path("License-Plate-Recognition/" . $response->file_path_out));
            }
            $queue->value = [
                'type' => 'plate',
                'path' => $path,
                'plates' => $response->liscense_plates,
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
