<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Jobs\DetectLP;
use App\Services\Queues\QueueSet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function detectLp(ImageRequest $request)
    {
        if ($request->file('image') == null) {
            return $this->response(['image' => "File image not found!"], 422);
        }
        $type_file = $request->image->getClientOriginalExtension();
        $path = 'temp/' . date("H") . "/detect-lp/" . time() . "_" . Str::random(10) . ".$type_file";
        Storage::disk('local')->put('public/' . $path, $request->file('image')->get());
        $data_insert = [
            'type' => config('detect.type.detect_lp'),
            'status' => 0,
            'data' => [
                'type' => 'image',
                'path' => $path,
            ]
        ];

        $queue = QueueSet::create($data_insert);

        dispatch(new DetectLP($queue->id))->onQueue('detect');
        return $this->response([
            'id' => $queue->id,
            'status' => $queue->status
        ]);
    }

    public function detectLpPage(ImageRequest $request)
    {
        if ($request->file('image') == null) {
            return $this->response(['image' => "File image not found!"], 422);
        }
        $type_file = $request->image->getClientOriginalExtension();
        $path = 'temp/' . date("H") . "/detect-lp/" . time() . "_" . Str::random(10) . ".$type_file";
        Storage::disk('local')->put('public/' . $path, $request->file('image')->get());
        $data_insert = [
            'type' => config('detect.type.detect_lp'),
            'status' => 0,
            'data' => [
                'type' => 'image',
                'path' => $path,
            ]
        ];

        $queue = QueueSet::create($data_insert);

        dispatch(new DetectLP($queue->id))->onQueue('detect');

        $queue_id = $queue->id;
        sleep(5);

        return redirect()->route('web.queues.show-page', ['id' => $queue_id]);
    }

    public function showImage()
    {
        return view('pages.image');
    }
}
