@extends("dashboard.layout.page")

<style>
    @media print {
        .dataTables_filter, .dt-buttons {display: none!important;} 
    }
</style>

@section("reportTitle")
{{ __('course_result_count') }} 
@endsection

@php 
    $builder = (new App\Course)->getViewBuilder(); 
@endphp  

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
                <option value="{{ $item->id }}" department="[{{ implode(",", App\CourseDepartment::where('course_id', $item->id)->select('id')->pluck('id')->toArray()) }}]" >{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
</div>
</div> 
@endif
<br>
<br>
@endsection

@section("reportContent")  
<br>
<div class="w3-padding w3-center w3-large" >
   {{ __('course_result_count') }} 
</div>
<br>
<br>
<div class="table-responsive" >

@if(request()->password == optional(App\Setting::find(8))->value && request()->password)
<table style="border-collapse: collapse;text-align: center;border-spacing: 0;width: 100%;display: table;border: 1px solid #ddd;direction: rtl" id="table" >
    <thead>
        <tr>  
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('course') }}</th>
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('doctors') }}</th> 
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('success percent') }}</th>
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('failed percent') }}</th>
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('success') }}</th>
            <th style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >{{ __('failed') }}</th>
             
        </tr>
    </thead>  
        
        <tbody>
        <?php
            $coursesId = [];
            $total1 = 0;
        ?>
        @foreach($courses as $item)
        @if (!isset($coursesId[$item->id]))
        
        <?php
            $coursesId[$item->id] = $item->id;
            
            $total = App\StudentResearch::where('course_id', $item->id)/*->where('upload_date', '<=', '2020-07-05')*/->where('result_id', '!=', null)->count();
            $sucess = App\StudentResearch::where('course_id', $item->id)/*->where('upload_date', '<=', '2020-07-05')*/->whereIn('result_id', [1, 4, 5])->count();
            $failed = App\StudentResearch::where('course_id', $item->id)/*->where('upload_date', '<=', '2020-07-05')*/->whereIn('result_id', [2, 3])->count();
            
            $successPercent = $total > 0? ($sucess / $total) * 100 : 0;
            $failedPercent = $total > 0? ($failed / $total) * 100 : 0;
        ?>
        @if ($total > 0)
        <tr>
            <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >
                {{ $item->name }}
            </td>
            <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >
                {{ implode(", ", $item->doctors()->pluck("name")->toArray()) }}
            </td> 
            <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" > 
                {{ round($successPercent, 1) }} %
            </td>
            <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" > 
                {{ round($failedPercent, 1) }} %
            </td>
            <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >
                {{ $sucess }} 
            </td>
            <td style="text-align: left;padding: 4px;border: 1px solid black;border-collapse: collapse;text-align: center;" >
                {{ $failed }} 
            </td>
             
        </tr>
        @php 
        $total1 += $failed;
        @endphp
        
        
        @endif
        @endif
        @endforeach
        </tbody>
</table>
@php
    $notUpload = App\StudentCourse::query()->join("users", "users.id", "=", "student_courses.student_id")->where('type', 'student')->where('users.graduated', 0)->count() - App\StudentResearch::where('created_at', '<=', '2020-07-05 00:00:00')->count();

@endphp
<ul>
    <li>
        {{ __('total failed') }} : {{ $total1 }}
    </li>
    <li>
        {{ __('not upload') }} : {{ $notUpload }}
    </li>
    <li>
        {{ __('total') }} : {{ $total1 + $notUpload }}
    </li>
</ul>
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
<script> 
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
        
        showPage('dashboard/report/course-result-count?password='+password);
    }
    function search() {  
        //alert(); 
        showPage("dashboard/report/course-result-count?"+$.param(filter.filter));
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
                //'print', 
            {
                extend: 'print',
                autoPrint: true,
                exportOptions: {
                    columns: ':visible'
                }
            }
            ],
            "sorting": [0, 'DESC'], 
         });

         formAjax(); 
         $('.course_id_select').select2();

        //$('.dt-buttons').addClass('visible-print-block');
        //$('#table_filter').addClass('visible-print-block');
    }); 
</script>
@endsection
