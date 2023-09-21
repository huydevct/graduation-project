@extends("layouts.master")
@section('content')
    @php
        $title_card = "Thêm một category mới";
        $url_action = route("admin.categories.store");
        if(!empty($category)){
             $title_card = "Cập nhật category: {$category->title}";
             $url_action = route("admin.categories.update",['id'=>$category->id]);
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
                    @if(isset($category))
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
                        <label class="form-label" for="sr_title">Title</label>
                        <input minlength="1" maxlength="255" class="form-control" id="sr_title" type="text"
                               name="title"
                               value="{{ !empty($category)?$category->title:old('title') }}">
                    </div>
                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_order">Order</label>
                        <input required minlength="2" maxlength="255" class="form-control" id="sr_order" type="number"
                               name="order"
                               value="{{ !empty($category) ? $category->order:old('order') ?? 0}}">
                    </div>
                    <div class="col-md-6 pb-2">
                        <label class="form-label" for="sr_active"></label>
                        <div class="form-check">
                            <input name="active" class="form-check-input" id="sr_active"
                                   type="checkbox" {!! (!empty($category)&&$category->active==1)||old('active')?'checked':'' !!}>
                            <label class="form-check-label" for="sr_active">Active</label>
                        </div>
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
