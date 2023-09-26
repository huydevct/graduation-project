@extends("layouts.master")
@section('content')
    @php
        $filter = [
            'device_id' =>(int) \Illuminate\Support\Facades\Request::get('device_id',null),
            'active' => \Illuminate\Support\Facades\Request::get('active'),
            'type' => \Illuminate\Support\Facades\Request::get('type'),
        ];
    @endphp
    <div class="container-lg">

    </div>
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-footer">
                    <a href="{{$queue->data['url']}}" target="_blank"><img src="{{$queue->data['url']}}" width="500" height="500"></a>
                    <a href="{{$queue->value['url']}}" target="_blank"><img src="{{$queue->value['url']}}" width="500" height="500"></a>
                </div>
                <div>
                    Plates
                    @foreach($queue->value['plates'] as $plate)
                        <b class="m-2">{{ $plate }}</b>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        {{--function clearParams() {--}}
        {{--    window.location.href = '<?php echo e(route('admin.devices.get')); ?>'--}}
        {{--}--}}

        {{--function block(serverId) {--}}
        {{--    Swal.fire({--}}
        {{--        title: 'Do you want to block this device?',--}}
        {{--        showCancelButton: true,--}}
        {{--        confirmButtonText: 'Block',--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            $.ajax({--}}
        {{--                type: "PUT",--}}
        {{--                url: "/admin/devices/block/" + serverId,--}}
        {{--                data: {--}}
        {{--                    _token: "{!! csrf_token() !!}"--}}
        {{--                },--}}
        {{--                success: function (result) {--}}
        {{--                    Swal.fire('Blocked!', '', 'success').then(result => {--}}
        {{--                        if ((result.isConfirmed)) {--}}
        {{--                            location.reload()--}}
        {{--                        }--}}
        {{--                    })--}}
        {{--                },--}}
        {{--                error: function (result) {--}}
        {{--                    Swal.fire('Block error!', '', 'info').then(result => {--}}
        {{--                        if ((result.isConfirmed)) {--}}
        {{--                            location.reload()--}}
        {{--                        }--}}
        {{--                    })--}}
        {{--                }--}}
        {{--            })--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}

        {{--function show(id) {--}}
        {{--    $.ajax({--}}
        {{--        type: "GET",--}}
        {{--        url: "/api/v1/queue/" + id,--}}
        {{--        data: {--}}
        {{--            _token: "{!! csrf_token() !!}"--}}
        {{--        },--}}
        {{--        success: function (result) {--}}
        {{--        },--}}
        {{--        error: function (result) {--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
    </script>
@endsection
