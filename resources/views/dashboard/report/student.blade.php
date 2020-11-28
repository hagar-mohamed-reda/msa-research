@extends("dashboard.layout.page")

@section("reportTitle")
{{ __('un registered student report') }} 
@endsection

@php 
    $builder = (new App\Student)->getViewBuilder(); 
@endphp  

@section("reportOptions")
 
<div class="w3-row box shadow w3-round report-content" style="margin: auto;padding: 10px" >
<div class="filters w3-row" id="filter"   > 

        <div class="form-group has-feedback w3-col l2 m2 s4 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div>   
        <div class="form-group has-feedback w3-col l5 m5 s4 w3-padding">
            <label>{{ __("select department") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.department_id=this.value" >    
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Department::all() as $item)
                <option value="{{ $item->id }}" v-if="filter.level_id=='{{ $item->level_id }}' || filter.level_id==0" >{{ $item->name }} - {{ optional($item->level)->name }}</option>
                @endforeach
            </select> 
        </div>
        <div class="form-group has-feedback w3-col l5 m5 s4 w3-padding">
            <label>{{ __("select level") }}</label>
            <select name="type" class="form-control select2" select2="off"  v-model="filter.level_id" >    
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
   {{ __('un registered students') }} 
</div>
<br>
<br>
<table class="table table-bordered" id="table" >
    <thead>
        <tr> 
            <th>{{ __('id') }}</th>
            <th>{{ __('code') }}</th>
            <th>{{ __('name') }}</th>
            <th>{{ __('national_id') }}</th> 
            <th>{{ __('set_number') }}</th>
            <th>{{ __('phone') }}</th>
            <th>{{ __('level') }}</th>
            <th>{{ __('department') }}</th>
            <th>{{ __('graduated') }}</th>
            <th>{{ __('courses') }}</th>
        </tr>
    </thead> 
    <tbody>
        
    </tbody>
</table>
<br>  
@endsection


@section("scripts") 
<script> 
    var table = null;

    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/student/data') }}?"+$.param(filter.filter)).load();
    }
    
      
var filter = new Vue({
    el: '#filter',
    data: { 
        filter: {
            un_complete: 1
        }
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
            "paging": false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'print',
                //'pdfHtml5'
            ],
            "sorting": [0, 'DESC'],
            "ajax": "{{ url('/dashboard/student/data?un_complete=1') }}",
            "columns":[ 
                { "data": "id" },  
                { "data": "code" },  
                { "data": "name" },
                { "data": "national_id" },
                { "data": "set_number" },    
                { "data": "phone" },
                { "data": "level_id" },
                { "data": "department_id" },
                { "data": "graduated" },
                { "data": "courses" },
            ]
         });

         formAjax(); 

    }); 
</script>
@endsection
