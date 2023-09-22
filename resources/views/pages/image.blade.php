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
{{--    <div class="container-lg">--}}
{{--        <div class="card mb-4">--}}
{{--            <div class="card-header d-flex justify-content-between">--}}
{{--                <div class="container text-center">--}}
{{--                    <form action="{!! route('admin.devices.get') !!}" method="get"--}}
{{--                          class="row justify-content-md-center">--}}
{{--                        <div class="col d-flex align-items-start">--}}
{{--                            <strong>Device List</strong>--}}
{{--                            <span class="ms-2">Danh sách devices</span>--}}
{{--                        </div>--}}
{{--                        <div class="col col-lg-2">--}}
{{--                            <select name="active" class="form-select" aria-label="Default select example">--}}
{{--                                <option value="" selected>Chọn Active</option>--}}
{{--                                <option value="1" {!! $filter['active']==1?'selected':'' !!}>Active</option>--}}
{{--                                <option value="0" {!! $filter['active']==='0'?'selected':'' !!}>InActive</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col col-lg-2">--}}
{{--                            <select name="type" class="form-select" aria-label="Default select example">--}}
{{--                                <option value="" selected>Chọn Type</option>--}}
{{--                                <option value="1" {!! $filter['type']==1?'selected':'' !!}>Android</option>--}}
{{--                                <option value="2" {!! $filter['type']==2?'selected':'' !!}>IOS</option>--}}
{{--                                <option value="0" {!! $filter['type']==='0'?'selected':'' !!}>Không xác định</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-auto">--}}
{{--                            <button type="button" class="btn btn-secondary" onclick="clearParams()">Clear</button>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-auto">--}}
{{--                            <button type="submit" class="btn btn-primary">Search</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}


{{--            </div>--}}
{{--            <div class="card-body">--}}
{{--                <table class="table table-striped">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th scope="col">ID</th>--}}
{{--                        <th scope="col">Name</th>--}}
{{--                        <th scope="col">Device ID</th>--}}
{{--                        <th scope="col">Active</th>--}}
{{--                        <th scope="col">Type</th>--}}
{{--                        <th scope="col">Queue Count</th>--}}
{{--                        <th scope="col">Created at</th>--}}
{{--                        <th scope="col"></th>--}}
{{--                        <th scope="col"></th>--}}
{{--                        <th scope="col"></th>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                    <tbody>--}}
{{--                    @foreach($devices as $index => $d)--}}
{{--                        <tr>--}}
{{--                            <th scope="row">--}}
{{--                                {{$d->id}}--}}
{{--                            </th>--}}
{{--                            <td>{{$d->name}}</td>--}}
{{--                            <td>{{$d->device_id}}</td>--}}
{{--                            <td>--}}
{{--                                @if($d->active==1)--}}
{{--                                    <span class="badge badge-sm bg-success ms-auto">--}}
{{--                                    Active--}}
{{--                                </span>--}}
{{--                                @else--}}
{{--                                    <span class="badge badge-sm bg-warning ms-auto">--}}
{{--                                    Block--}}
{{--                                </span>--}}
{{--                            @endif--}}
{{--                            <td>--}}
{{--                                @if($d->type == 1)--}}
{{--                                    Android--}}
{{--                                @elseif($d->type == 2)--}}
{{--                                    IOS--}}
{{--                                @else--}}
{{--                                    Không xác định--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>{{ $d->queue_count }}</td>--}}
{{--                            <td>{{$d->created_at}}</td>--}}
{{--                            <td>--}}
{{--                                <a href="{!! route('admin.devices.show',['id' => $d->id]) !!}" title="View Queue"--}}
{{--                                   type="button" class="btn btn-primary btn-sm">--}}
{{--                                    View--}}
{{--                                </a>--}}
{{--                            </td>--}}
{{--                            @if($d->active == 1)--}}
{{--                                <td>--}}
{{--                                    <button onclick="block({{$d->id}})" title="Chặn"--}}
{{--                                            class="btn btn-danger btn-sm">--}}
{{--                                        Block--}}
{{--                                    </button>--}}
{{--                                </td>--}}
{{--                            @elseif($d->active == 0)--}}
{{--                                <td>--}}
{{--                                    <button onclick="unblock({{$d->id}})" title="Bỏ Chặn"--}}
{{--                                            class="btn btn-danger btn-sm">--}}
{{--                                        Unblock--}}
{{--                                    </button>--}}
{{--                                </td>--}}
{{--                            @endif--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}
{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}
{{--            <div class="card-footer">--}}
{{--                {{ $devices->links('vendor.pagination.simple-bootstrap-5') }}--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
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

        {{--function unblock(serverId) {--}}
        {{--    Swal.fire({--}}
        {{--        title: 'Do you want to unblock this device?',--}}
        {{--        showCancelButton: true,--}}
        {{--        confirmButtonText: 'Unblock',--}}
        {{--    }).then((result) => {--}}
        {{--        if (result.isConfirmed) {--}}
        {{--            $.ajax({--}}
        {{--                type: "PUT",--}}
        {{--                url: "/admin/devices/unblock/" + serverId,--}}
        {{--                data: {--}}
        {{--                    _token: "{!! csrf_token() !!}"--}}
        {{--                },--}}
        {{--                success: function (result) {--}}
        {{--                    Swal.fire('Unblocked!', '', 'success').then(result => {--}}
        {{--                        if ((result.isConfirmed)) {--}}
        {{--                            location.reload()--}}
        {{--                        }--}}
        {{--                    })--}}
        {{--                },--}}
        {{--                error: function (result) {--}}
        {{--                    Swal.fire('Unblock error!', '', 'info').then(result => {--}}
        {{--                        if ((result.isConfirmed)) {--}}
        {{--                            location.reload()--}}
        {{--                        }--}}
        {{--                    })--}}
        {{--                }--}}
        {{--            })--}}
        {{--        }--}}
        {{--    })--}}
        {{--}--}}
    </script>
@endsection
