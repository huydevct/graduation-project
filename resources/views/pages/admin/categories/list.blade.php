@extends("layouts.master")
@section('content')
    @php
        $filter = [
            'device_id' =>(int) \Illuminate\Support\Facades\Request::get('device_id',null),
            'active' => \Illuminate\Support\Facades\Request::get('active'),
            'order_sort' => \Illuminate\Support\Facades\Request::get('order_sort', null),
        ];
    @endphp
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <div class="container text-center">
                    <form action="{!! route('admin.categories.get') !!}" method="get"
                          class="row justify-content-md-center">
                        <div class="col d-flex align-items-start">
                            <strong>Categories List</strong>
                            <span class="ms-2">Danh sách Categories</span>
                            <a href="{!! route('admin.categories.create') !!}">
                                <button type="button" class="btn btn-success btn-sm ms-1">
                                    <span class="cil-contrast btn-icon ml-2"></span> Tạo mới
                                </button>
                            </a>
                        </div>

                        <div class="col-sm-auto">
                            <select name="order_sort" class="form-select" aria-label="Default select example"
                                    onclick="sortOrder(this.value)">
                                <option value="" selected="">Order Sort</option>
                                <option value="asc" {!! $filter['order_sort']=='asc'?'selected':'' !!}>ASC</option>
                                <option value="desc" {!! $filter['order_sort']=='desc'?'selected':'' !!}>DESC</option>
                            </select>
                        </div>

                        <div class="col col-lg-2">
                            <select name="active" class="form-select" aria-label="Default select example">
                                <option value="" selected>Chọn Active</option>
                                <option value="1" {!! $filter['active']==1?'selected':'' !!}>Active</option>
                                <option value="0" {!! $filter['active']==='0'?'selected':'' !!}>InActive</option>
                            </select>
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
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Order</th>
                        <th scope="col">Active</th>
                        <th scope="col">Created at</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $index => $c)
                        <tr>
                            <th scope="row">
                                {{$c->id}}
                            </th>
                            <td>{{$c->title}}</td>
                            <td>{{$c->order}}</td>
                            <td>
                                @if($c->active==1)
                                    <span class="badge badge-sm bg-success ms-auto">
                                    Active
                                </span>
                                @else
                                    <span class="badge badge-sm bg-warning ms-auto">
                                    Block
                                </span>
                            @endif
                            <td>{{$c->created_at}}</td>
                            <td width="100">
                                <a href="{!! route('admin.categories.edit',['id' => $c->id]) !!}" title="Cập nhật"
                                   type="button" class="btn btn-primary btn-sm">
                                    Edit
                                </a>
                            </td>
                            <td width="100">
                                <button onclick="hardDelete({{$c->id}})" title="Xóa"
                                        class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </td>
                            @if($c->active == 1)
                                <td width="100">
                                    <button onclick="block({{$c->id}})" title="Chặn"
                                            class="btn btn-danger btn-sm">
                                        Block
                                    </button>
                                </td>
                            @elseif($c->active == 0)
                                <td width="100">
                                    <button onclick="unblock({{$c->id}})" title="Bỏ Chặn"
                                            class="btn btn-danger btn-sm">
                                        Unblock
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $categories->links('vendor.pagination.simple-bootstrap-5') }}
            </div>
        </div>
    </div>
    <script>
        function clearParams() {
            window.location.href = '<?php echo e(route('admin.categories.get')); ?>'
        }

        function sortOrder(sort) {
            window.location.href = removeParam("order_sort")
            insertParam("order_sort", sort)
        }

        function removeParam(parameter) {
            var url = document.location.href;
            var urlparts = url.split('?');

            if (urlparts.length >= 2) {
                var urlBase = urlparts.shift();
                var queryString = urlparts.join("?");

                var prefix = encodeURIComponent(parameter) + '=';
                var pars = queryString.split(/[&;]/g);
                for (var i = pars.length; i-- > 0;)
                    if (pars[i].lastIndexOf(prefix, 0) !== -1)
                        pars.splice(i, 1);
                url = urlBase + '?' + pars.join('&');
                window.history.pushState('', document.title, url); // added this line to push the new url directly to url bar .

            }
            return url;
        }

        function insertParam(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);

            // kvp looks like ['key1=value1', 'key2=value2', ...]
            var kvp = document.location.search.substr(1).split('&');
            let i = 0;

            for (; i < kvp.length; i++) {
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }

            if (i >= kvp.length) {
                kvp[kvp.length] = [key, value].join('=');
            }

            // can return this or...
            let params = kvp.join('&');

            // reload page with new params
            document.location.search = params;
        }

        function hardDelete(categoryId) {
            Swal.fire({
                title: 'Do you want to delete this category?',
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: "/admin/categories/delete/" + categoryId,
                        data: {
                            _token: "{!! csrf_token() !!}"
                        },
                        success: function (result) {
                            Swal.fire('Deleted!', '', 'success').then(result => {
                                if ((result.isConfirmed)) {
                                    location.reload()
                                }
                            })
                        },
                        error: function (result) {
                            Swal.fire('Deleted error!', '', 'info').then(result => {
                                if ((result.isConfirmed)) {
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            })
        }

        function block(serverId) {
            Swal.fire({
                title: 'Do you want to block this Category?',
                showCancelButton: true,
                confirmButtonText: 'Block',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "PUT",
                        url: "/admin/categories/block/" + serverId,
                        data: {
                            _token: "{!! csrf_token() !!}"
                        },
                        success: function (result) {
                            Swal.fire('Blocked!', '', 'success').then(result => {
                                if ((result.isConfirmed)) {
                                    location.reload()
                                }
                            })
                        },
                        error: function (result) {
                            Swal.fire('Block error!', '', 'info').then(result => {
                                if ((result.isConfirmed)) {
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            })
        }

        function unblock(serverId) {
            Swal.fire({
                title: 'Do you want to unblock this Category?',
                showCancelButton: true,
                confirmButtonText: 'Unblock',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "PUT",
                        url: "/admin/categories/unblock/" + serverId,
                        data: {
                            _token: "{!! csrf_token() !!}"
                        },
                        success: function (result) {
                            Swal.fire('Unblocked!', '', 'success').then(result => {
                                if ((result.isConfirmed)) {
                                    location.reload()
                                }
                            })
                        },
                        error: function (result) {
                            Swal.fire('Unblock error!', '', 'info').then(result => {
                                if ((result.isConfirmed)) {
                                    location.reload()
                                }
                            })
                        }
                    })
                }
            })
        }
    </script>
@endsection
