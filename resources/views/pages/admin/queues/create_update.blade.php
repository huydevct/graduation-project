@extends("layouts.master")
@section('content')
    @php
        $title_card = "Thêm một máy chủ mới";
        $url_action = route("admin.servers.store");
        if(!empty($server)){
             $title_card = "Cập nhật máy chủ: {$server->name}";
             $url_action = route("admin.servers.update",['id'=>$server->id]);
        }
    @endphp
    <div class="container-lg">

        <form method="POST"
              action="{!! $url_action !!}"
              enctype="multipart/form-data" class="card  col-md-12">
            <div class="card-header d-flex justify-content-between">
                <div class="flex-column">
                    <strong>{{$title_card}}</strong>
                </div>
            </div>
            <div class="card-body">
                <div class="row col-md-12">
                    @csrf
                    @if(isset($server))
                        @method('put')
                    @endif

                    @if ($errors->any())
                        @dd($errors->all())
                    @endif

                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_name">Tên máy chủ</label>
                        <input required minlength="2" maxlength="255" class="form-control" id="sr_name" type="text"
                               name="name"
                               value="{{ !empty($server)?$server->name:old('name') }}">
                        @error('name')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-6  pb-2">
                        <label class="form-label" for="sr_ip">Địa chỉ IP</label>
                        <input required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="sr_ip"
                               type="text" name="ip" value="{{ !empty($server)?$server->ip:old('ip') }}">
                        @error('ip')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-md-6  pb-2">
                        <label class="form-label" for="sr_ip">Max Connect</label>
                        <input required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" class="form-control" id="sr_max_connect"
                               type="number" name="max_connect"
                               value="{{ !empty($server)?$server->max_connect:old('max_connect') }}">
                        @error('max_connect')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-md-6  pb-2">
                        <label class="form-label" for="sr_ip">Lng</label>
                        <input class="form-control" id="sr_max_connect"
                               type="number" name="lng" value="{{ !empty($server)?$server->lng:old('lng') }}">
                        @error('lng')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-md-6  pb-2">
                        <label class="form-label" for="sr_ip">Lat</label>
                        <input class="form-control" id="sr_max_connect"
                               type="number" name="lat" value="{{ !empty($server)?$server->lat:old('lat') }}">
                        @error('lat')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_country">Quốc gia</label>
                        <select name="country_id" id="sr_country" class="form-select form-select-sm col-md-12"
                                aria-label=".form-select-lg example">
                            <option selected=""
                                    disabled>{!! !empty($server)?$server->country_name:"Chọn một quốc gia" !!}</option>
                            @foreach($countries as $country)
                                <option value="{!! (int)$country->id !!}">
                                    {!! $country->name !!}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_country"></label>
                        <div class="form-check">
                            <input name="active" class="form-check-input" id="sr_active"
                                   type="checkbox" {!! (!empty($server)&&$server->active==1)||old('active')?'checked':'' !!}>
                            <label class="form-check-label" for="sr_active">Active</label>
                        </div>
                        @error('active')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_country">Status</label>
                        <select name="status" id="sr_country" class="form-select form-select-sm col-md-12"
                                aria-label=".form-select-lg example">
                            <option selected=""
                                    disabled>{!! !empty($server) && $server->status == 0? $status[0]:"Sẵn Sàng" !!}</option>
                            @foreach($status as $k=>$value)
                                <option value="{!! (int)$k !!}">
                                    {!! $value !!}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="sr_config">Config</label>
                        @if(!empty($server))
                            <textarea name="config_text" class="form-control" id="sr_config"
                                      rows="5">{{ !empty($server)?$server->config:old('config') }}</textarea>
                        @endif

                        <input type="file" name="config" accept=".ovpn"
                               value="{!! !empty($server)?$server->config:old('config') !!}">
                        @error('config')
                        <div class="invalid-feedback" >{{$message}}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection
