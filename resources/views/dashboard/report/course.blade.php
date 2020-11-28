@extends("dashboard.layout.page")

@section("reportTitle")
{{ __('course report') }} 
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
</div> 
<br>
<br>
@endsection

@section("reportContent")  
<br>
<div class="w3-padding w3-center w3-large" >
   {{ __('courses') }} 
</div>
<br>
<br>
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
            
            
            @if (Auth::user()->type == 'admin')
            <th>{{ __('students') }}</th>
            @endif 
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
            
            @if (Auth::user()->type == 'admin')
            { "data": "students" },
            @endif  
            ]
         });

         formAjax(); 

    }); 
</script>
@endsection
