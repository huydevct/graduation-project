<?php

namespace App\Http\Controllers;

use App\Http\Requests\FolderImageRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Requests\VideoRequest;
use App\Jobs\DetectLPFolder;
use App\Jobs\DetectLP;
use App\Jobs\DetectLpVideo;
use App\Jobs\DetectObject;
use App\Services\Queues\QueueSet;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function detectLpFolder(FolderImageRequest $request)
    {
        $array_path = [];
        if (is_array($request->images)) {
            foreach ($request->images as $image) {
                $type_file = $image->getClientOriginalExtension();
                $path = 'temp/' . date("H") . "/detect-lp/" . time() . "_" . Str::random(10) . ".$type_file";
                $ok = Storage::disk('local')->put("public/".$path, $image->get());
                $array_path[] = $path;
            }
        }else{
            return $this->response(['message' => [
                'Images is required!'
            ]], 422);
        }

        $data_insert = [
            'type' => config('detect.type.detect_lp'),
            'status' => 0,
            'data' => [
                'type' => 'images',
                'path' => $array_path,
            ]
        ];

        $queue = QueueSet::create($data_insert);

        dispatch(new DetectLPFolder($queue->id))->onQueue('detect');
        return $this->response([
            'id' => $queue->id,
            'status' => $queue->status
        ]);
    }
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

    public function detectLpVideo(Request $request)
    {
        if ($request->file('video') == null) {
            return $this->response(['video' => "File video not found!"], 422);
        }
        $type_file = $request->video->getClientOriginalExtension();
        $path = 'temp/' . date("H") . "/detect-lp-video/" . time() . "_" . Str::random(10) . ".$type_file";
        Storage::disk('local')->put('public/' . $path, $request->file('video')->get());
        $data_insert = [
            'type' => config('detect.type.detect_lp_video'),
            'status' => 0,
            'data' => [
                'type' => 'video',
                'path' => $path,
            ]
        ];


        $queue = QueueSet::create($data_insert);

        dispatch(new DetectLpVideo($queue->id))->onQueue('detect_video');
        return $this->response([
            'id' => $queue->id,
            'status' => $queue->status
        ]);
    }

//    public function detectLpVideo(Request $request)
//    {
//        if ($request->file('video') == null) {
//            return $this->response(['video' => "File video not found!"], 422);
//        }
//        $type_file = $request->video->getClientOriginalExtension();
//        $path = 'temp/' . date("H") . "/detect-lp-video/" . time() . "_" . Str::random(10) . ".$type_file";
//        Storage::disk('local')->put('public/' . $path, $request->file('video')->get());
//        $data_insert = [
//            'type' => config('detect.type.detect_lp_video'),
//            'status' => 0,
//            'data' => [
//                'type' => 'video',
//                'path' => $path,
//            ]
//        ];
//
//
//        $queue = QueueSet::create($data_insert);
//
//        dispatch(new DetectLpVideo($queue->id))->onQueue('detect_video');
//        return $this->response([
//            'id' => $queue->id,
//            'status' => $queue->status
//        ]);
//    }

    public function detectObject(Request $request)
    {
        if ($request->file('image') == null) {
            return $this->response(['image' => "File image not found!"], 422);
        }
        $type_file = $request->image->getClientOriginalExtension();
        $path = 'temp/' . date("H") . "/detect-object/" . time() . "_" . Str::random(10) . ".$type_file";
        Storage::disk('local')->put('public/' . $path, $request->file('image')->get());
        $data_insert = [
            'type' => config('detect.type.detect_object'),
            'status' => 0,
            'data' => [
                'type' => 'image',
                'path' => $path,
            ]
        ];


        $queue = QueueSet::create($data_insert);

        dispatch(new DetectObject($queue->id))->onQueue('detect');
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
        sleep(10);

        return redirect()->route('web.queues.show-page', ['id' => $queue_id]);
    }

    public function showImage()
    {
        return view('pages.image');
    }
}
