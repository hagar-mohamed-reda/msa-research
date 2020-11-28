@extends("dashboard.layout.app")

@section("title")
{{ __('researchs') }}
@endsection
@php
    $builder = (new App\Research)->getViewBuilder();
@endphp

@section("content")
<div class="filters w3-row" id="filter"   > 
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div>  
        <div class="form-group has-feedback w3-col l9 m9 s6 w3-padding">
            <label>{{ __("select course") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.course_id=this.value" >
                <option value="0">{{ __('select all') }}</option>
                @if (Auth::user()->type ==  'admin')
                @foreach(App\Course::all() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach 
                
                @else
                
                @foreach(Auth::user()->toDoctor()->courses()->get() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach 
                @endif
            </select> 
        </div> 
</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            @foreach($builder->cols as $col)
            <th>{{ $col['label'] }}</th>
            @endforeach
            <th>{{ __('student_researches') }}</th> 
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional")
<!-- add modal -->
<div class="modal fade"  role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('add research') }}</center>
      </div>
      <div class="modal-body">
        {!! $builder->loadAddView() !!}
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('edit research') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("headers") 
<button class=" btn btn-primary btn-flat" onclick="$('.filters').slideToggle(300)" >
    <i class="fa fa-filter" ></i> {{ __('filters') }}
</button>
 
@endsection

@section("js")

@if (!Auth::user()->_can('add research'))
<script>
    $('.floatbtn-research').remove();
</script>
@endif
<script>
    var table = null;

    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/research/data') }}?"+$.param(filter.filter)).load();
    }
    
      
var filter = new Vue({
    el: '#filter',
    data: { 
        filter: {}
    },
    methods: { 
    },
    computed: {
    },
    watch: {
    }
});

$(document).ready(function() {
    table = $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 5,
        "sorting": [0, 'DESC'],
        "ajax": "{{ url('/dashboard/research/data') }}",
        "columns":[
            @foreach($builder->cols as $col)
            { "data": "{{ $col['name'] }}" },
            @endforeach
            { "data": "student_researches" },
            { "data": "action" }
        ]
     });

     formAjax(false, function(r){
         $("select").select2();
     });

});
</script>
@endsection
