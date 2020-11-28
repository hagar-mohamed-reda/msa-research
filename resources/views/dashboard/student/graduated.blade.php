@extends("dashboard.layout.app")

<link rel="stylesheet" href="{{ url('/') }}/css/uploader.css">
@section("title")
{{ __('graduated') }}
@endsection 

@section("content")
<div>
    <div class="alert alert-danger w3-xxlarge" >
        {{ __('you must wait the exam') }}
    </div>
</div> 

@endsection
 