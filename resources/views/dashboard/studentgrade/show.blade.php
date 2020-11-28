@extends("dashboard.layout.app")
 
@section("title")
{{ __('student grades') }}
@endsection
 

@section("content")
<div class="w3-padding w3-pale-red w3-large alert" >
    <label>{{ __('gpa') }} : {{ optional(Auth::user()->toStudent()->grades()->first())->gpa }}</label> 
</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr class="w3-dark-gray" >
            <th>#</th>
            <th>{{ __('course') }}</th>
            <th>{{ __('grade') }}</th> 
        </tr>
    </thead>
    <tbody>
        @foreach(Auth::user()->toStudent()->grades()->get() as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ optional($item->course)->name }}</td>
            <td>{{ $item->grade }}</td> 
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
  
@section("js")
   
<script>
    $('.app-add-button').remove();
</script> 
<script> 
var table = null;


$(document).ready(function() {
    table =  $('#table').DataTable({
        "paging": false, 
        "sorting": [0, 'DESC'], 
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ], 
     });
 

});
</script>
@endsection
