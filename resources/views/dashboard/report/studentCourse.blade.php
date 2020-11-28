@extends("dashboard.layout.page")

@section("reportTitle")
{{ __('student courses report') }} 
@endsection

@section("reportOptions")
 
@endsection

@section("reportContent")  
<br>
<div class="w3-padding w3-center w3-large" >
   {{ __('students courses') }} 
</div>
<br>
<br>
<table class="table table-bordered" >
    <tr>
        <th>{{ __('student') }}</th>
        <th>{{ __('courses') }}</th>
    </tr>
    
    @foreach(App\User::students()->get() as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>
            @foreach($item->toStudent()->courses()->get() as $item)
            <span class="label label-default" style="margin: 5px" >{{ $item->name }}</span>
            @endforeach
        </td>
    </tr>
    @endforeach
</table> 
<br>  
@endsection


@section("scripts")
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">  
</script> 
@endsection
