@extends("dashboard.layout.page")

@section("reportTitle")
{{ __('student_not_upload_research_report') }} 
@endsection

@php 
    $builder = (new App\Course)->getViewBuilder(); 
@endphp  

@section("reportOptions")
 
<div class="w3-row box shadow w3-round report-content" style="margin: auto;padding: 10px" >
<div class="filters w3-row" id="filter"   > 

        <div class="form-group has-feedback w3-col l3 m3 s4 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div>  
        <div class="form-group has-feedback w3-col l5 m5 s4 w3-padding">
            <label>{{ __("select department") }}</label>
            <select name="type" class="form-control select2" select2="off"  onchange="filter.filter.department_id=this.value" >    
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Department::all() as $item)
                <option value="{{ $item->id }}" v-if="filter.level_id=='{{ $item->level_id }}' || filter.level_id=='0'" >{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l4 m4 s4 w3-padding">
            <label>{{ __("select level") }}</label>
            <select name="type" class="form-control select2" select2="off" v-model="filter.level_id" >  
                <option value="0">{{ __('select all') }}</option> 
                @foreach(App\Level::all() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
</div>
</div> 
<br>
<br>
@endsection

@section("reportContent")  
<br>
<div class="w3-padding w3-center w3-large" >
   {{ __('student_not_upload_research') }} 
</div>
<br>
<br>
<div class="table-responsive" >

<table class="table table-bordered" id="table" >
    <thead>
        <tr>   
            <th>{{ __('name') }}</th> 
            <th>{{ __('level') }}</th>
            <th>{{ __('department') }}</th> 
            <th>{{ __('register_course_count') }}</th> 
            <th>{{ __('register_course') }}</th> 
            <th>{{ __('not_uploaded_research_count') }}</th>
            <th>{{ __('not_uploaded_research') }}</th>
        </tr>
    </thead>  
        
        <tbody>  
        </tbody>
</table>
</div>
<br>  


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

@endsection


@section("scripts") 
<script> 
    var table = null;

    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/report/student-not-upload-research/data') }}?"+$.param(filter.filter)).load();
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
            "pageLength": 10,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'print',
                //'pdfHtml5'
            ],
            "sorting": [0, 'DESC'],
            "ajax": "{{ url('/dashboard/report/student-not-upload-research/data') }}",
            "columns":[ 
                { "data": "name" },       
                { "data": "level_id" },  
                { "data": "department_id" },  
                { "data": "register_course_count" },  
                { "data": "register_course" },    
                { "data": "not_uploaded_research_count" },    
                { "data": "not_uploaded_research" }
            ]
         });

         formAjax(); 

    }); 
</script>
 
@endsection
