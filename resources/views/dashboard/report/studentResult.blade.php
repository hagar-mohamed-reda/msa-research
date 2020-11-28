@extends("dashboard.layout.page")

@section("reportTitle")
{{ __('student results report') }} 
@endsection

@section("reportOptions")
 
@if(request()->password == optional(App\Setting::find(8))->value && request()->password)
<div class="w3-row box shadow w3-round report-content" style="margin: auto;padding: 10px" >
<div class="filters w3-row" id="filter"   > 

        <div class="form-group has-feedback w3-col l3 m3 s4 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div>  
        <div class="form-group has-feedback w3-col l3 m3 s4 w3-padding">
            <label>{{ __("select department") }}</label>
            <select name="type" class="form-control select2" select2="off"  onchange="filter.filter.department_id=this.value" >    
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Department::all() as $item)
                <option value="{{ $item->id }}" v-if="filter.level_id=='{{ $item->level_id }}' || filter.level_id=='0'" >{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l3 m3 s4 w3-padding">
            <label>{{ __("select level") }}</label>
            <select name="type" class="form-control select2" select2="off" v-model="filter.level_id" >  
                <option value="0">{{ __('select all') }}</option> 
                @foreach(App\Level::all() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("course") }}</label>
            <select name="type" class="form-control select2 course_id_select" select2="on"  onchange="filter.filter.course_id=this.value"   >  
                <option value="0">{{ __('select all') }}</option> 
                @foreach(Auth::user()->type == 'admin'? App\Course::all() : Auth::user()->toDoctor()->courses()->get() as $item)
                <option value="{{ $item->id }}"   >{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
</div>
</div> 
@endif
<br>
<br>
@endsection

@section('header')
<div style="float: right" >
    <span>{{ __('level') }}</span> : <span style="margin-left: 10px;margin-right: 10px" >{{ optional(App\Level::find(request()->level_id))->name }}</span> 
    <span>{{ __('department') }}</span> : <span style="margin-left: 10px;margin-right: 10px" >{{ optional(App\Department::find(request()->department_id))->name }}</span> 
    <br>
    <span style="text-align: right" >{{ __('student_year') }}  2019/2020</span>
</div>
<br><br>
@endsection

@section("reportContent")  
<!--
<br>
<div style="font-size: 20px;text-align: center;padding: 7px" >
   {{ __('students results') }} 
</div>
<br>
-->
<br>
<div class="table-responsive" >
@if(request()->password == optional(App\Setting::find(8))->value && request()->password)
@if (count($students) > 0)
<table style="border-collapse: collapse;text-align: center;border-spacing: 0;width: 100%;display: table;border: 1px solid #ddd;direction: rtl" id="table" >
    <thead>
        <tr>
        <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('counter') }}</th>
        <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('set_number') }}</th>
        <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('student') }}</th>
        @foreach($courses as $item) 
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ $item->name }}</th> 
        @endforeach
        </tr>
    </thead>
    
    <tbody>
    @foreach($students as $student)
    <tr> 
        <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ $loop->iteration }}</td>
        <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;"  style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ $student->set_number }}</td>
        <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ $student->name }}</td>
        @php
            //$studentResearches = App\StudentResearch::where('student_id', $student->id)->whereIn('course_id', $coursesIds)->orderBy('course_id')->get();
            //$counter = (count($coursesIds) - count($studentResearches)) > 0? count($coursesIds) - count($studentResearches) : 0;
        @endphp
        
        @foreach($courses as $course)
        <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >  
            @if (App\StudentResearch::where('student_id', $student->id)->where('course_id', $course->id)->first())
            {{ optional(optional(App\StudentResearch::where('student_id', $student->id)->where('course_id', $course->id)->first())->result)->name }}  
            @else
            {{ __('no research uploaded') }}
            @endif
        </td> 
        @endforeach  
    </tr>
    @endforeach
    </tbody>
</table>
@else
<center class="w3-large" >{{ __('please select a department') }}</center>
@endif
@else 
    <section class="content w3-margin" style="direction: ltr">
        <div> 
            <div class="form-group has-feedback">
                <label>ادخل كلمة السر الخاصه بالنتيجه</label>
                <input required="" type="password" name="password" class="form-control password" placeholder="{{ __('password') }}">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <center class="form-group w3-center" >
                <button class="btn btn-primary" onclick="login()" >{{ __('login') }}</button>
            </center>
        </div>
         
        <!-- /.row -->
    </section>
@endif
</div>
<br>  
@endsection


@section("scripts")
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript"> 
var table = null;

    @if (request()->password) 
        @if(request()->password != optional(App\Setting::find(8))->value)
        error("{{ __('password error') }}");
        @endif
    @endif

    function login() {
        var password = $('.password').val();
        if (password.length <= 0)
            return error("{{ __('enter the password') }}");
        
        showPage('dashboard/report/studentresult?password='+password);
    }
    
    function search() {  
        showPage('dashboard/report/studentresult?' + $.param(filter.filter));  
    }
    
      
    var filter = new Vue({
        el: '#filter',
        data: { 
            filter: {
                password: '{{ request()->password }}',
                level_id: null
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
            "paging": false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                //'csvHtml5',
                'print', 
            ],
            "sorting": [0, 'DESC'], 
         });
 
         $('.course_id_select').select2();

    }); 
</script> 
@endsection
