@extends('layouts.master')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">License PLate Detection</h1>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary"><a class="nav-link" href="{{ route("web.image.show") }}">Image</a></button>
                <button type="button" class="btn btn-primary"><a class="nav-link" href="#">Video</a></button>
            </div>
        </div>
    </div>
</div>
@endsection


