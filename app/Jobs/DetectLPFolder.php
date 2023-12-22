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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class DetectLPFolder implements ShouldQueue
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

        QueueSet::update($this->queue_id, [
            'status' => 1
        ]);

        try {
            if (empty($queue)) {
                echo "Error: Not Found queue: {$queue->status}" . PHP_EOL;
                throw new \Exception('Run job DetectLPFolder fail: not found queue!');
            }
            if ($queue->status != 0) {
                echo "Error: Job status: {$queue->status}" . PHP_EOL;
                throw new \Exception("Run job DetectLPFolder fail: Job status: {$queue_id}:  {$queue->status}!");
            }

            $array_path = $queue->data['path'];
            $time_start = microtime(true);
            $licenses = [0];
            $array_path_res = [];
            $licenses_txt = [];

            foreach ($array_path as $path) {
                $params = [
                    [
                        'name' => 'image',
                        'contents' => Storage::disk('local')->get("public/$path"),
                        'filename' => 'image.jpg'
                    ],
                ];

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
                $file_out = file_get_contents(base_path("temp/detect-lp/" . $response->file_path_out));
                Storage::disk('local')->put('public/' . $path, $file_out);
                if (File::exists(base_path("temp/detect-lp/" . $response->file_path_out))) {
                    unlink(base_path("temp/detect-lp/" . $response->file_path_out));
                }

                $array_path_res[] = $path;
                $licenses[] = [
                    $path => $response->liscense_plates
                ];
                $licenses_txt[] =  $response->liscense_plates;
            }

//            $zip = new ZipArchive;
//            $zipFileName = 'temp/' . date("H") . "/detect-lp/" . time() . "_" . Str::random(10) . "-out.zip";
//            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
//                foreach ($filesToZip as $file) {
//                    $zip->addFile($file, basename($file));
//                }
//
//                $zip->close();
//            } else {
//                throw new \Exception("Failed to create the zip file.");
//            }
            $path_txt = 'temp/' . date("H") . "/detect-lp/" . time() . "_" . Str::random(10) . "-out.json";
            Storage::disk('local')->put("public/".$path_txt, json_encode($licenses_txt));

            $queue->value = [
                'type' => 'plate-folder',
                'path' => $array_path_res,
                'plates' => $licenses,
                'json_file' => $path_txt,
            ];

            $queue->process_time = $process_time;
            $queue->status = 2;
            $queue->save();
        } catch (\Exception $exception) {
            $queue->status = 3;
            $queue->error = $exception->getMessage();
            $queue->save();
            echo "Error: Process return error: {$exception->getMessage()}" . PHP_EOL;
        }
    }
}
