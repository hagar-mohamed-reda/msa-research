@extends("dashboard.layout.app")

<style>
    .form-group {
        height: 80px
    }
    
    label {
        color: black!important;
    }
    
    .form {
        direction: rtl;
    }
</style>
@section("title")
{{ __('courses') }}
@endsection
@php 
    $builder = (new App\Course)->getViewBuilder(); 
@endphp  

@section("content")
@if (Auth::user()->type == 'admin')
<div class="filters w3-row" id="filter"   > 

        <div class="form-group has-feedback w3-col l3 m3 s4 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div>  
        <div class="form-group has-feedback w3-col l4 m4 s4 w3-padding">
            <label>{{ __("filter with has research") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.has_research=this.value" >  
                <option value="">{{ __('select all') }}</option> 
                <option value="1">{{ __('course has researchs') }}</option> 
                <option value="2">{{ __('course not has researchs') }}</option>
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l5 m5 s4 w3-padding">
            <label>{{ __("select doctor") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.doctor_id=this.value" >    
                <option value="">{{ __('select all') }}</option> 
            @foreach(App\User::doctors()->get() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
</div>
@endif
<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            
            @if (Auth::user()->type == 'admin')
            @foreach($builder->cols as $col)
            <th>{{ $col['label'] }}</th>   
            @endforeach
            
            @else
            <th>{{ __('name') }}</th>  
            @endif
            
            <th>{{ __('doctors') }}</th>
            <th>{{ __('researchs') }}</th>
            <th>{{ __('departments') }}</th>
            
            
            @if (Auth::user()->type != 'student')
            <th>{{ __('student_researches') }}</th>
            <th>{{ __('student_not_has_researches') }}</th>
            <th>{{ __('register_students') }}</th>
            @endif
            <th></th>
        </tr>
    </thead> 
    <tbody>
        
    </tbody>
</table> 

@endsection

@section("additional")
<!-- add modal --> 
<div class="modal fade" tabindex="-1" role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal- " role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('add course') }}</center>
      </div>
      <div class="modal-body"> 
        @include('dashboard.course.add')
      </div> 
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 


<!-- edit modal --> 
<div class="modal fade" tabindex="-1" role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal- " role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('edit course') }}</center>
      </div>
      <div class="modal-body editModalPlace">
         
      </div> 
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  

<!-- assign modal --> 
<div class="modal fade" tabindex="-1" role="dialog" id="assignModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body assign-modal-place">
          
      </div> 
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  
@endsection

@section("headers") 
@if (Auth::user()->type == 'admin')
<button class=" btn btn-primary btn-flat" onclick="$('.filters').slideToggle(300)" >
    <i class="fa fa-filter" ></i> {{ __('filters') }}
</button>
<select class="btn btn-default" data-select2="off" onchange="changePageLengthDataTable(this.value)" >
    <option value="10" >10</option>
    <option value="50" >50</option>
    <option value="100" >100</option>
    <option value="1000" >1000</option>
</select>
@endif
 
@endsection
@section("js") 
@if (Auth::user()->type != 'admin')
<script>
    $('.app-add-button').remove();
</script>
@endif
<script>
    function changePageLengthDataTable(len) {
        table.page.len(len).draw();
    }

    function searchDoctor(key) {
        if (key.length <= 0)
            return $(".doctor-list-item").show();
        
        $(".doctor-list-item").hide();
        $(".doctor-list-item").each(function(){
            if ($(this).text().indexOf(key) >= 0) {
                $(this).show();
            }
        });
    }
    
    var table = null;

    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/course/data') }}?"+$.param(filter.filter)).load();
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
            "pageLength": {{ Auth::user()->type == 'student'? 100 : 5 }},
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "sorting": [0, 'DESC'],
            "ajax": "{{ url('/dashboard/course/data') }}",
            "columns":[
                
            @if (Auth::user()->type == 'admin')
                @foreach($builder->cols as $col)
                { "data": "{{ $col['name'] }}" },     
                @endforeach
            @else
                
                { "data": "name" },     
            @endif
                { "data": "doctors" },  
                { "data": "researchs" }, 
            { "data": "departments" },
            
            @if (Auth::user()->type != 'student')
            { "data": "student_researches" },
            { "data": "student_not_researches" },
            { "data": "students" },
            @endif 
                { "data": "action" }
            ]
         });

         formAjax(); 

    }); 
</script>
@endsection
