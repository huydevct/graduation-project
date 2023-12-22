<?php

namespace App\Jobs;

use App\Services\LicensePlate\LicensePlateGet;
use App\Services\Queues\QueueGet;
use App\Services\Queues\QueueSet;
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

class DetectLpVideo implements ShouldQueue
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
                throw new \Exception('Run job DetectLPVideo fail: not found queue!');
            }
            if ($queue->status != 0) {
                echo "Error: Job status: {$queue->status}" . PHP_EOL;
                throw new \Exception("Run job DetectLPVideo fail: Job status: {$queue_id}:  {$queue->status}!");
            }
            $queue_data = $queue->data;
            $time_start = microtime(true);
            $params = [
                [
                    'name' => 'video',
                    'contents' => Storage::disk('local')->get("public/{$queue_data['path']}"),
                    'filename' => 'video.mp4'
                ],
                [
                    'name' => 'queue_id',
                    'contents' => $this->queue_id,
                ]
            ];
            QueueSet::update($this->queue_id, [
                'status' => 1
            ]);
            $client = new Client();
            $response = $client->post(config('detect.detect_lp_video_url') . '/detect-lp-video', [
                'multipart' => $params,
                'timeout' => 240
            ]);
            $process_time = round(microtime(true) - $time_start, 3);
            if ($response->getStatusCode() != 200)
                throw new \Exception("Detect LP video error!");
            $path = 'temp/' . date("H") . "/detect-lp-video/" . time() . "_" . Str::random(10) . "-out.mp4";
            $res = $response->getBody()->getContents();
//            $response = json_decode($responses);
//            $file_out = file_get_contents(base_path("temp/detect-lp-video/" . $response->file_path_out));
//            Storage::disk('minio')->put($path, $res);
            Storage::disk('local')->put('public/' . $path, $res);
//            if (File::exists(base_path("temp/detect-lp-video/" . $response->file_path_out))) {
//                unlink(base_path("temp/detect-lp/" . $response->file_path_out));
//            }

            $lps = LicensePlateGet::getByQueueId($this->queue_id);
            if(empty($lps)){
                $plates = [$this->queue_id];
            }else{
                $plates = $lps->lps;
            }
            $queue->value = [
                'type' => 'video plate',
                'path' => $path,
                'plates' => $plates,
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
