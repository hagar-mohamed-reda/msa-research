@extends("dashboard.layout.app")

@section("title")
{{ __('doctors') }}
@endsection
@php
    $builder = (new App\Doctor)->getViewBuilder();
@endphp

@section("content")
<div>

</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            @foreach($builder->cols as $col)
            @if ($col['name'] != 'password')
            <th>{{ $col['label'] }}</th>
            @endif
            @endforeach
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional")
<!-- add modal
<div class="modal fade"  role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('add doctor') }}</center>
      </div>
      <div class="modal-body">
        {!! $builder->loadAddView() !!}
      </div>
    </div>
  </div>
</div>
-->

<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('edit doctor') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("js")

@if (!Auth::user()->_can('add doctor'))
<script>
    $('.app-add-button').remove();
</script>
@endif
<script>
    $('.app-add-button').click(function(){
        window.open('http://lms.seyouf.sphinxws.com/ar/dashboard/doctors/create');
    });

$(document).ready(function() {
     $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 5,
        "sorting": [0, 'DESC'],
        "ajax": "{{ url('/dashboard/doctor/data') }}",
        "columns":[
            @foreach($builder->cols as $col)
            @if ($col['name'] != 'password')
            { "data": "{{ $col['name'] }}" },
            @endif
            @endforeach
            { "data": "action" }
        ]
     });

     formAjax();

});
</script>
@endsection
