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
                <form action="{!! route('api.v1.images.detect') !!}" method="POST" class="card" enctype="multipart/form-data">
                    <input accept="image/*" name="image" class="form-control" type="file">

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            Detect
                        </button>
                    </div>
                </form>
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

        function show(id) {
            $.ajax({
                type: "GET",
                url: "/api/v1/queue/" + id,
                data: {
                    _token: "{!! csrf_token() !!}"
                },
                success: function (result) {
                },
                error: function (result) {
                }
            })
        }
    </script>
@endsection
