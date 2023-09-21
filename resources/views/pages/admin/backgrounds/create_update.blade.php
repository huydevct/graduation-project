@extends("layouts.master")
@section('content')
    @php
        $title_card = "Thêm một background mới";
        $url_action = route("admin.backgrounds.store");
        if(!empty($background)){
             $title_card = "Cập nhật background: {$background->id}";
             $url_action = route("admin.backgrounds.update",['id'=>$background->id]);
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
                    @if(isset($background))
                        @method('put')
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_order">Order</label>
                        <input required minlength="2" maxlength="255" class="form-control" id="sr_order" type="number"
                               name="order"
                               value="{{ !empty($background)?$background->order:old('order') ?? 0 }}">
                    </div>

                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_category">Category</label>
                        <select name="category_id" id="sr_category" class="form-select form-select-sm col-md-12"
                                aria-label=".form-select-lg example">
                            <option selected=""
                                    disabled>{!! !empty($background->category)?$background->category['title']:"Chọn một Category" !!}</option>
                            @foreach($categories as $category)
                                <option value="{!! (int) $category->id!!}">
                                    {!! $category->title !!}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_active"></label>
                        <div class="form-check">
                            <input name="active" class="form-check-input" id="sr_active"
                                   type="checkbox" {!! (!empty($background)&&$background->active==1)||old('active')?'checked':'' !!}>
                            <label class="form-check-label" for="sr_active">Active</label>
                        </div>
                    </div>

                    <div id="background-upload">
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
    <script>
        window.pathImage = "{{ !empty($background) ? $background->path['file_name'] : "" }}";
        window.$VueData = {
            images: {!! !empty($background) ? json_encode($background->path) : json_encode([]) !!},
            errors: {!! json_encode($errors->getMessages()) !!},
            csrf_token: "{!! csrf_token() !!}"
        }

    </script>
    @vite('resources/js/vuejs/widgets/upload_a_background.js')
@endsection
