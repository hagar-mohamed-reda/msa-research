@extends("dashboard.layout.app")
 
@section("title")
{{ __('courses not match in student grade sheet') }}
@endsection
 

@section("content")
<table class="table table-bordered" id="table" >
    <thead>
        <tr class="w3-dark-gray" >
            <th>#</th>
            <th>{{ __('course') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach(DB::table('test')->select('name')->groupBy('name')->get() as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
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
