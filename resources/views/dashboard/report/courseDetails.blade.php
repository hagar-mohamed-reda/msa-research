@extends("dashboard.layout.page")

@section("reportTitle")
{{ __('course_details_report') }}
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
   {{ __('course_details_report') }}
</div>
<br>
<br>
<div class="table-responsive" >

<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            <th>{{ __('course') }}</th>
            <th>{{ __('doctors') }}</th>
            <th>{{ __('researchs') }}</th>
            <th>{{ __('register_student_count') }}</th>
            <th>{{ __('student_count_uploaded') }}</th>
            <th>{{ __('student_count_not_upload') }}</th>
            <th>{{ __('student_count_has_result') }}</th>
            <th>{{ __('student_count_not_has_result') }}</th>
            <th>{{ __('student_has_exam') }}</th>

        </tr>
    </thead>

        <tbody>
        <?php
            $coursesId = [];
        ?>
        @foreach($courses as $item)
        @if (!isset($coursesId[$item->id]))

        <?php
            $coursesId[$item->id] = $item->id;
        ?>
        <tr>
            <td>
                {{ $item->name }}
            </td>
            <td>
                {{ implode(", ", $item->doctors()->pluck("name")->toArray()) }}
            </td>
            <td>
                {{ $item->researches()->count() }}
            </td>
            <td>
                {{ $item->studentCourses()->count() }}
                <br>
                <button class="btn btn-default fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}')" >  </button>
            </td>
            <td>
                {{ App\StudentResearch::where('course_id', $item->id)->count() }}
                <br>
                <button class="btn btn-default fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?has_research=1&course_id={{ $item->id }}')" >  </button>
            </td>
            <td>
                {{ $item->studentCourses()->join("students", "students.id", "=", "student_courses.student_id")->where('students.graduated', 0)->count() - App\StudentResearch::where('course_id', $item->id)->count() }}
                <br>
                <button class="btn btn-default fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?has_research=0&course_id={{ $item->id }}')" >  </button>
            </td>
            <td>
                {{ App\StudentResearch::where('course_id', $item->id)->where('result_id', "!=", null)->count() }}
                <br>
                <button class="btn btn-default fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?has_result=1&course_id={{ $item->id }}')" >  </button>
            </td>
            <td>
                {{ App\StudentResearch::where('course_id', $item->id)->where('result_id', null)->count() }}
                <br>
                <button class="btn btn-default fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?has_result=0&course_id={{ $item->id }}')" >  </button>
            </td>
            <td>
                {{ App\StudentCourse::query()
                    ->join("students", "students.id", "=", "student_courses.student_id")
                    ->where('course_id', $item->id)->where('students.graduated', 1)->count() }}
                <br>
                <button class="btn btn-default fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/course/show') . '/' . $item->id }}?graduated=1&course_id={{ $item->id }}')" >  </button>
            </td>
        </tr>
        @endif
        @endforeach
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
        showPage("dashboard/report/course-details?"+$.param(filter.filter));
    }


    var filter = new Vue({
        el: '#filter',
        data: {
            filter: {
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

         formAjax();

    });
</script>
@endsection
