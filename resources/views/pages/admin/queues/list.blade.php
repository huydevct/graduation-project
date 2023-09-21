@extends("layouts.master")
@section('content')
    @php
        $filter = [
            'queue_id' =>(int) \Illuminate\Support\Facades\Request::get('queue_id',null),
            'sort' =>\Illuminate\Support\Facades\Request::get('sort',null),
            'id_sort' =>\Illuminate\Support\Facades\Request::get('id_sort',null),
            'day_sort' =>\Illuminate\Support\Facades\Request::get('day_sort',null),
            'status' =>(int) \Illuminate\Support\Facades\Request::get('status',null),
            'type' =>(int) \Illuminate\Support\Facades\Request::get('type',null),
        ];
    @endphp
    <div class="container-fluid">
        <div>
            <strong>Queues List</strong>
            <span class="medium ms-1">Danh sách Queues</span>
        </div>
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-center">
                <div class="text-center">
                    <div class="col-lg-auto d-flex justify-content-center">
                        <form action="{!! route('admin.queues.get') !!}" method="get"
                              class="row justify-content-md-center">
                            <div class="col-md-auto d-flex">
                                <select name="id_sort" class="form-select" aria-label="Default select example"
                                        onclick="sortId(this.value)">
                                    <option value="" selected="">ID Sort</option>
                                    <option value="asc" {!! $filter['id_sort']=='asc'?'selected':'' !!}>ASC</option>
                                    <option value="desc" {!! $filter['id_sort']=='desc'?'selected':'' !!}>DESC</option>
                                </select>
                            </div>

                            <div class="col-md-auto">
                                <select name="sort" class="form-select" aria-label="Default select example"
                                        onclick="sortDuration(this.value)">
                                    <option value="" selected="">Duration Sort</option>
                                    <option value="asc" {!! $filter['sort']=='asc'?'selected':'' !!}>ASC</option>
                                    <option value="desc" {!! $filter['sort']=='desc'?'selected':'' !!}>DESC</option>
                                </select>
                            </div>

                            <div class="col-sm-auto">
                                <select name="day_sort" class="form-select" aria-label="Default select example"
                                        onclick="sortDay(this.value)">
                                    <option value="" selected="">Day Sort</option>
                                    <option value="asc" {!! $filter['day_sort']=='asc'?'selected':'' !!}>ASC</option>
                                    <option value="desc" {!! $filter['day_sort']=='desc'?'selected':'' !!}>DESC</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <input type="text" class="form-control" value="{{$filter['queue_id'] != 0 ? $filter['queue_id'] : ""}}" placeholder="Device ID">
                            </div>

                            <div class="col-sm-auto">
                                <select name="status" class="form-select" aria-label="Default select example">
                                    <option value="" selected>Chọn Status</option>
                                    <option value="0" {!! $filter['status']==='0'?'selected':'' !!}>Chờ</option>
                                    <option value="1" {!! $filter['status']==1?'selected':'' !!}>Đang Xử Lý</option>
                                    <option value="2" {!! $filter['status']==2?'selected':'' !!}>Thành Công</option>
                                    <option value="3" {!! $filter['status']==3?'selected':'' !!}>Lỗi</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select name="type" class="form-select" aria-label="Default select example">
                                    <option value="" selected="">Chọn Type</option>
                                    <option value="1" {!! $filter['type']==1?'selected':'' !!}>Remove Background</option>
                                    <option value="2" {!! $filter['type']==2?'selected':'' !!}>Upscale Image</option>
                                    <option value="3" {!! $filter['type']==3?'selected':'' !!}>Convert Image To Anime</option>
                                    <option value="4" {!! $filter['type']==4?'selected':'' !!}>Remove Object</option>
                                </select>
                            </div>

                            <div class="col col-lg-2">
                                <input type="text" class="form-control" name="datefilter"
                                       value="{{ \Illuminate\Support\Facades\Request::get('datefilter',null) }}"
                                       placeholder="Created at"/>
                            </div>

                            <div class="col-md-auto">
                                <button type="button" class="btn btn-secondary" onclick="clearParams()">Clear</button>
                            </div>
                            <div class="col-md-auto">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Device Name</th>
                        <th scope="col">Device ID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Input</th>
                        <th scope="col">Output</th>
                        <th scope="col">Error</th>
                        <th scope="col">Server Name</th>
                        <th scope="col">Server IP</th>
                        <th scope="col">Duration(s)</th>
                        <th scope="col">Created at</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($queues as $index => $q)
                        <tr>
                            <th scope="row">
                                {{$q->id}}
                            </th>
                            <td>{{$q->device->name ?? ''}}</td>
                            <td>{{$q->device->id ?? ''}}</td>
                            <td>
                                @if($q->type == 1)
                                    <span class="badge badge-sm bg-success ms-auto">
                                    Remove Background
                                    </span>
                                @elseif($q->type == 2)
                                    <span class="badge badge-sm bg-info ms-auto">
                                    Upscale Image
                                    </span>
                                @elseif($q->type == 3)
                                    <span class="badge badge-sm bg-warning ms-auto">
                                    Convert Image To Anime
                                    </span>
                                @elseif($q->type == 4)
                                    <span class="badge badge-sm bg-primary ms-auto">
                                    Remove Object
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($q->status == 0)
                                    <span class="badge badge-sm bg-info ms-auto">
                                    Chờ
                                    </span>
                                @elseif($q->status == 1)
                                    <span class="badge badge-sm bg-primary ms-auto">
                                    Đang xử lý
                                    </span>
                                @elseif($q->status == 2)
                                    <span class="badge badge-sm bg-success ms-auto">
                                    Thành công
                                    </span>
                                @elseif($q->status == 3)
                                    <span class="badge badge-sm bg-danger ms-auto">
                                    Lỗi
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($q->data))
                                    @if(isset($q->data['url']))
                                        <a href="{{$q->data['url']}}" target="_blank">Open File</a>
                                    @endif

                                    @if(isset($q->data['url_mask']))
                                        <a href="{{$q->data['url_mask']}}" target="_blank">Open Mask File</a>
                                    @endif

                                    @if(isset($q->data['url_image']))
                                        <a href="{{$q->data['url_image']}}" target="_blank">Open Image File</a>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if(!empty($q->value))
                                    @if(isset($q->value['url']))
                                        <a href="{{$q->value['url']}}" target="_blank">Open File</a>
                                    @endif

                                    @if(isset($q->value['url_mask']))
                                        <a href="{{$q->value['url_mask']}}" target="_blank">Open Mask File</a>
                                    @endif

                                    @if(isset($q->value['url_image']))
                                        <a href="{{$q->value['url_image']}}" target="_blank">Open Image File</a>
                                    @endif
                                @else
                                    No Output
                                @endif
                            </td>
                            <td>
                                @if(!empty($q->error))
                                    <button type="button" class="btn btn-primary" data-coreui-toggle="modal"
                                            data-coreui-target="#showError">
                                        Show Error
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="showError" tabindex="-1"
                                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Error of Queue
                                                        ID: {{ $q->id }}</h5>
                                                    <button type="button" class="btn-close" data-coreui-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ $q->error }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-coreui-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    No Error
                                @endif
                            </td>

                            <td>{{$q->server_name}}</td>
                            <td>{{$q->ip_server ?? '127.0.0.1'}}</td>
                            <td>{{$q->process_time}}</td>
                            <td>{{$q->created_at}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $queues->links('vendor.pagination.simple-bootstrap-5') }}
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"
            defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript">
        function clearParams() {
            window.location.href = '<?php echo e(route('admin.queues.get')); ?>'
        }

        function sortId(sort) {
            insertParam("id_sort", sort)
        }

        function sortDuration(sort) {
            insertParam("sort", sort)
        }

        function sortDay(sort) {
            window.location.href = removeParam("id_sort")
            insertParam("day_sort", sort)
        }
        function removeParam(parameter)
        {
            var url=document.location.href;
            var urlparts= url.split('?');

            if (urlparts.length>=2)
            {
                var urlBase=urlparts.shift();
                var queryString=urlparts.join("?");

                var prefix = encodeURIComponent(parameter)+'=';
                var pars = queryString.split(/[&;]/g);
                for (var i= pars.length; i-->0;)
                    if (pars[i].lastIndexOf(prefix, 0)!==-1)
                        pars.splice(i, 1);
                url = urlBase+'?'+pars.join('&');
                window.history.pushState('',document.title,url); // added this line to push the new url directly to url bar .

            }
            return url;
        }

        function insertParam(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);

            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i=0;

            for(; i<kvp.length; i++){
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }

            if(i >= kvp.length){
                kvp[kvp.length] = [key,value].join('=');
            }

            // can return this or...
            let params = kvp.join('&');

            // reload page with new params
            document.location.search = params;
        }

        $(function () {
            var datefilter = "{{ \Illuminate\Support\Facades\Request::get('datefilter',null) }}"
            var dates = datefilter.split(' - ');
            var startDate;
            var endDate;
            if (datefilter === "") {
                startDate = moment().startOf('hour')
                endDate = moment().startOf('hour').add(24, 'hour')
            } else {
                startDate = moment(dates[0], 'YYYY-MM-DD');
                endDate = moment(dates[1], 'YYYY-MM-DD');
            }

            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                startDate: startDate,
                endDate: endDate,
                locale: {
                    cancelLabel: 'Clear'
                }
            }, function (start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

        });
    </script>
@endsection
