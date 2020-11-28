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

        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div>  
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("level") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.level_id=this.value" >  
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Level::all() as $item)
                <option value="{{ $item->id }}" 
                
                v-if="[{{ implode(", ", App\CourseDepartment::join('departments', 'course_departments.department_id', '=', 'departments.id')->where('level_id', $item->id)->pluck('course_id')->toArray()) }}].includes(parseInt(filter.course_id)) || filter.course_id == 0">{{ $item->name }}</option>
                @endforeach
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("deparmtent") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.deparmtent_id=this.value" >  
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Department::all() as $item)
                <option value="{{ $item->id }}"  
                v-if="[{{ implode(", ", App\CourseDepartment::where('department_id', $item->id)->pluck('course_id')->toArray()) }}].includes(parseInt(filter.course_id)) || filter.course_id == 0"     >{{ $item->name }} - {{ optional($item->level)->name }}</option>
                @endforeach
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("course") }}</label>
            <select name="type" class="form-control select2" v-model="filter.course_id"   >  
                <option value="0">{{ __('select all') }}</option> 
                @foreach(Auth::user()->type == 'admin'? App\Course::all() : Auth::user()->toDoctor()->courses()->get() as $item)
                <option value="{{ $item->id }}" department="[{{ implode(",", App\CourseDepartment::where('course_id', $item->id)->select('id')->pluck('id')->toArray()) }}]" >{{ $item->name }}</option>
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
<div class="table-responsive" >

<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            <td>{{ __('course') }}</td>
            <td>{{ __('register_students') }}</td> 
            <td>{{ __('level 1') }}</td>
            <td>{{ __('level 2') }}</td>
            <td>{{ __('level 3') }}</td>
            <td>{{ __('level 4') }}</td>
            <td>{{ __('level 4 has research') }}</td>
            <td>{{ __('level 4 not has research') }}</td>
        </tr>
    </thead> 
    <tbody>
        @foreach($courses as $item)
        <tr>
            <td>
                
                {{ $item->name }}
            </td>
            <td>
                {{ App\StudentCourse::where('course_id', $item->id)->count() }}
                <br>
                <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}')" > {{ __('register student') }} </i> 
            </td>
            <td>
                {{ App\StudentCourse::join("users", "users.id", "=", "student_courses.student_id")->where('level_id', 1)->where('course_id', $item->id)->count() }}
                <br>
                <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?level_id=1')" > {{ __('register student') }} </i> 
            </td>
            <td>
                {{ App\StudentCourse::join("users", "users.id", "=", "student_courses.student_id")->where('level_id', 2)->where('course_id', $item->id)->count() }}
                
                <br>
                <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?level_id=2')" > {{ __('register student') }} </i> 
            </td>
            <td>
                <ul style="width: 100px" class="w3-tiny" >
                @foreach(App\Department::where('level_id', 3)->get() as $depart)
                <li>
                    {{ $depart->name }} : {{ App\StudentCourse::join("users", "users.id", "=", "student_courses.student_id")->where('department_id', $depart->id)->where('level_id', 3)->where('course_id', $item->id)->count() }}
                </li>
                @endforeach
                </ul> 
                <br>
                <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?level_id=3')" > {{ __('register student') }} </i> 
            </td>
            <td>
                <ul style="width: 100px" class="w3-tiny" >
                @foreach(App\Department::where('level_id',4)->get() as $depart)
                <li>
                    {{ $depart->name }} : {{ App\StudentCourse::join("users", "users.id", "=", "student_courses.student_id")->where('department_id', $depart->id)->where('level_id', 4)->where('course_id', $item->id)->count() }}
                </li>
                @endforeach
                </ul> 
                <br>
                <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?level_id=4')" > {{ __('register student') }} </i> 
            </td>
            <td>
                {{ App\StudentCourse::join("users", "users.id", "=", "student_courses.student_id")->where('graduated', '0')->where('level_id', 4)->where('course_id', $item->id)->count() }}
            </td>
            <td>
                {{ App\StudentCourse::join("users", "users.id", "=", "student_courses.student_id")->where('graduated', '1')->where('level_id', 4)->where('course_id', $item->id)->count() }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
<br>  

<!-- edit modal --> 
<div class="modal fade" tabindex="-1" role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal- " role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('register students') }}</center>
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
        showPage('dashboard/report/student-course-research?' + $.param(filter.filter));
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
            
         });
 

    }); 
</script>
@endsection
