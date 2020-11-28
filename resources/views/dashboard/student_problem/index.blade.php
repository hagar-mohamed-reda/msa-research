@extends("dashboard.layout.app")

@section("title")
{{ __('problems') }}
@endsection 
@section("content")
<div class="w3-row filters" >
    <div class="w3-col l4 m4 s12 form-group w3-padding" >
        <label>{{ __('level') }}</label>
        <select class="form-control" onchange="filter.filter.level_id=this.value" >
            <option>{{ __('level') }}</option>
            @foreach(App\Level::all() as $item)
            <option value="{{ $item->id }}" >{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="w3-col l4 m4 s12 form-group w3-padding" >
        <label>{{ __('department') }}</label>
        <select class="form-control" onchange="filter.filter.department_id=this.value" >
            <option>{{ __('department') }}</option>
            @foreach(App\Department::all() as $item)
            <option value="{{ $item->id }}" >{{ $item->name }} / {{ optional($item->level)->name }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="w3-col l4 m4 s12 form-group" >
        <button class="btn btn-primary" onclick="search()" >
            {{ __('search') }}
        </button>
    </div>
</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr>  
            <th>{{ __('name') }}</th> 
            <th>{{ __('code') }}</th> 
            <th>{{ __('phone') }}</th> 
            <th>{{ __('type') }}</th> 
            <th>{{ __('complaint') }}</th> 
            <th>{{ __('status') }}</th> 
            <th>{{ __('employee') }}</th> 
            <th>{{ __('employee comment') }}</th> 
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional") 

<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('edit problem') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("headers")
 
    
<button class=" btn btn-primary   w3-round-xxlarge" onclick="$('.filters').slideToggle(300)" >
    <i class="fa fa-filter" ></i> {{ __('filters') }}
</button>


<button class="btn btn-default w3-round-xxlarge " onclick="filter.filter={};filter.filter.status='default';search()" >  <i class="fa fa-circle" ></i>  {{ __('new') }} </button>

<button class="btn btn-success w3-round-xxlarge " onclick="filter.filter={};filter.filter.status='success';search()" >  <i class="fa fa-check" ></i>  {{ __('success') }} </button>

<button class="btn btn-warning w3-round-xxlarge " onclick="filter.filter={};filter.filter.status='warning';search()" >  <i class="fa fa-exclamation-triangle" ></i>  {{ __('warning') }} </button>

<button class="btn btn-danger w3-round-xxlarge " onclick="filter.filter={};filter.filter.status='error';search()" >  <i class="fa fa-exclamation-circle" ></i>  {{ __('error') }} </button>
@endsection
@section("js")
 
<script>
    var table = null;
    
    $('.app-add-button').remove();
    
    function updateStatus(id, status, comment, button) { 
        console.log(status);
        if (!status || status.length <= 0)
            return error("{{ __('please choose the status') }}");
        
        var data = {
            _token: '{{ csrf_token() }}',
            comment: comment,
            status: status
        };
        $(button).html('<i class="fa fa-spin fa-spinner" ></i>');
        $.post('{{ url("/dashboard/problem/update") }}/'+id, $.param(data), function(r) {
            if (r.status == 1)
                success(r.message);
            else 
                error(r.message);
            
            $('#table').DataTable().ajax.reload();
            $(button).html('<i class="fa fa-check" ></i>');
        });
    }
    
    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/student-problem/data') }}?"+$.param(filter.filter)).load();
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
        "ajax": "{{ url('/dashboard/student-problem/data') }}",
        "columns":[ 
            { "data": "name" }, 
            { "data": "code" },
            { "data": "phone" },  
            { "data": "type" }, 
            { "data": "notes" }, 
            { "data": "status" }, 
            { "data": "user_id" },
            { "data": "comment" },
            { "data": "action" }
        ]
     });

     formAjax();

});
</script>
@endsection
