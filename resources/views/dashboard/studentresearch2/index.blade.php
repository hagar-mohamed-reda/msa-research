@extends("dashboard.layout.app")

@section("title")
{{ __('studentresearchs') }}
@endsection
@php
    $builder = (new App\StudentResearch)->getViewBuilder();
@endphp

@section("content")
<div class="filters w3-row" id="filter"   > 
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("department") }}</label> 
            <select name="type" class="form-control" select2="off"  onchange="filter.filter.department_id=this.value" >
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Department::all() as $item)
                <option value="{{ $item->id }}"  
                v-if="[{{ implode(", ", App\CourseDepartment::where('department_id', $item->id)->pluck('course_id')->toArray()) }}].includes(parseInt(filter.course_id)) || filter.course_id == 0"  >{{ $item->name }} - {{ optional($item->level)->name }}</option>
                @endforeach  
            </select> 
        </div> 
        
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("research") }}</label> 
            <select name="type" class="form-control" select2="off"  onchange="filter.filter.research_id=this.value" >
                <option value="">{{ __('select all') }}</option> 
                @if (Auth::user()->type ==  'admin')
                @foreach(App\Research::all() as $item)
                <option value="{{ $item->id }}" v-if="(filter.course_id=='{{ $item->course_id }}') || filter.course_id == 0"  >{{ $item->title }}</option>
                @endforeach 
                @else 
                @foreach(Auth::user()->toDoctor()->researchs()->get() as $item)
                <option value="{{ $item->id }}" v-if="(filter.course_id=='{{ $item->course_id }}') || filter.course_id == 0"  >{{ $item->title }}</option>
                @endforeach 
                @endif
            </select> 
        </div>  
        
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("select course") }}</label>
            <select name="type" class="form-control select2" select2="on" onchange="filter.filter.course_id=this.value"   >
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
         
        <div class="form-group has-feedback w3-col l3 m3 s6 w3-padding">
            <label>{{ __("result") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.result_id=this.value" >
                <option value="">{{ __('select all') }}</option>
                @foreach(App\Result::all() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach 
            </select> 
        </div> 
        
        <div class="form-group has-feedback w3-col l4 m4 s6 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div> 
      
        <div class="form-group has-feedback w3-col l4 m4 s6 w3-padding">
            <label>{{ __("student") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.student_id=this.value"   >
                <option value="">{{ __('choose student') }}</option>
                @foreach(App\User::students()->get() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach 
            </select> 
        </div>
         
        
        <div class="form-group has-feedback w3-col l4 m4 s6 w3-padding">
            <label>{{ __("level") }}</label> 
            <select name="type" class="form-control" select2="off"  onchange="filter.filter.level_id=this.value" >
                <option value="">{{ __('select all') }}</option> 
                @foreach(App\Level::all() as $item)
                <option value="{{ $item->id }}" 
                v-if="[{{ implode(", ", App\CourseDepartment::join('departments', 'course_departments.department_id', '=', 'departments.id')->where('level_id', $item->id)->pluck('course_id')->toArray()) }}].includes(parseInt(filter.course_id)) || filter.course_id == 0"
                >{{ $item->name }}</option>
                @endforeach  
            </select> 
        </div> 
        
</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr> 
            <th>{{ __('student code') }}</th> 
            <th>{{ __('student') }}</th> 
            <th>{{ __('course code') }}</th> 
            <th>{{ __('course') }}</th> 
            <th>{{ __('research') }}</th> 
            <th>{{ __('file') }}</th> 
            <th>{{ __('result') }}</th> 
            <th>{{ __('upload_date') }}</th> 
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional")
<!-- download modal -->
<div class="modal fade"  role="dialog" id="downloadModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('download student researches') }}</center>
      </div>
      <div class="modal-body"> 
        <div class="w3-row" > 
            <div class="form-group has-feedback w3-col w3-padding">
                <label>{{ __("select level") }}</label>
                <select name="level_id" class="form-control select2" select2="off" v-model="download.level_id" >  
                    <option value="0">{{ __('select all') }}</option> 
                    @foreach(App\Level::all() as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select> 
            </div> 
            <div class="form-group has-feedback w3-col   w3-padding">
                <label>{{ __("select department") }}</label>
                <select name="department_id" class="form-control select2" select2="off"  v-model="download.department_id" >    
                    <option value="">{{ __('select all') }}</option> 
                    @foreach(App\Department::all() as $item)
                    <option value="{{ $item->id }}" v-if="download.level_id=='{{ $item->level_id }}' || download.level_id=='0'" >{{ $item->name }}</option>
                    @endforeach
                </select> 
            </div> 
            <!--
             v-if="[{{ implode(", ", DB::table('course_departments')->where('course_id', $item->id)->pluck('department_id')->toArray()) }}].includes(parseInt(download.department_id)) || download.department_id == 0"
            -->
            <div class="form-group has-feedback w3-col w3-padding">
                <label>{{ __("course") }}</label>
                <select name="course_id" class="form-control select2 course_id_select" select2="on"  onchange="download.download.course_id=this.value"     >  
                    <option value="0">{{ __('select all') }}</option> 
                    @foreach(Auth::user()->type == 'admin'? App\Course::all() : Auth::user()->toDoctor()->courses()->get() as $item)
                    <option value="{{ $item->id }}"
                
                       
                    >{{ $item->name }}</option>
                    @endforeach
                </select> 
            </div> 
            <div class="form-group has-feedback w3-col w3-padding">
                <center>
                    <button   class="btn btn-primary btn-flat"  onclick="downloadFile(this)" >
                        {{ __('download') }}
                    </button>
                </center>
            </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- add modal -->
<div class="modal fade"  role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('add studentresearch') }}</center>
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
        <center class="modal-title w3-xlarge">{{ __('edit studentresearch') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

 
<div class="modal fade"  role="dialog" id="publishModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('publish result') }}</center>
      </div>
      <div class="modal-body editModalPlace">
            <center class="w3-xlarge" >
                 {{ __('are your sure to publish the result of students') }}
            </center>
            
            <br>
            <ul>
                <li class="w3-large" >
                    {{ __('total of students has result') }} : {{ Auth::user()->toDoctor()->studentResearchs()->where('result_id', '!=', null)->count() }}
                </li> 
            </ul> 
            <center>
                
                <button class="btn btn-primary" onclick="publishResultAll()" >{{ __('publish result') }}</button>
            </center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section("headers")
<!--
    <button class="btn btn-warning btn-flat" onclick="$('#publishModal').modal('show')" >
        {{ __('publish result') }}
    </button>
-->
    
<select class="btn btn-default" data-select2="off" onchange="changePageLengthDataTable(this.value)" >
    <option value="10" >10</option>
    <option value="50" >50</option>
    <option value="100" >100</option>
    <option value="1000" >1000</option>
</select>
<button class=" btn btn-primary btn-flat" onclick="$('.filters').slideToggle(300)" >
    <i class="fa fa-filter" ></i> {{ __('filters') }}
</button>

<button class=" btn btn-primary btn-flat" onclick="$('#downloadModal').modal('show')" >
    <i class="fa fa-download" ></i> {{ __('download all researchs') }}
</button>
@endsection

@section("js")
  
<script>
    var table = null;
    $('.app-add-button').remove();
    
    function changePageLengthDataTable(len) {
        table.page.len(len).draw();
    }
    
    function downloadAll() {
        $(".student-research-span").each(function(){
            downloadURI(this.getAttribute('data-src'), this.getAttribute('data-student'));
            //window.open(this.getAttribute('data-src')); 
        });
    }
    function downloadURI(uri, name) 
    {
        var link = document.createElement("a");
        // If you don't know the name or want to use
        // the webserver default set name = ''
        link.setAttribute('download', name);
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        link.remove();
    }
    
    function updateStatus(id, status, button) { 
        var opened = $('.student-research-action-'+id).parent().parent().find('.student-research-span').attr('data-open');
        console.log(opened);
        if (opened == 'off')
            return error('{{ __("please open the research first") }}');
            
        console.log(status);
        //if (!status || status.length <= 0)
        //    return error("{{ __('please choose the result') }}");
        
        var data = {
            _token: '{{ csrf_token() }}',
            status: status
        };
        $(button).html('<i class="fa fa-spin fa-spinner" ></i>');
        $.post('{{ url("/dashboard/studentresearch/status/update") }}/'+id, $.param(data), function(r) {
            if (r.status == 1)
                success(r.message);
            else 
                error(r.message);
            
            $('#table').DataTable().ajax.reload();
            $(button).html('<i class="fa fa-check" ></i>');
        });
    }
    
    function publishResultAll() { 
        swal({
            title: "😧 هل انت متاكد?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (willDelete) {
            if (willDelete) {
                $('.publish-result').click();
                
            } else {
            }
        });
    }
    
    
    function publishResult(id, button) { 
        
                var data = {
                    _token: '{{ csrf_token() }}'  
                };
                $(button).html('<i class="fa fa-spin fa-spinner" ></i>');
                $.post('{{ url("/dashboard/studentresearch/publish-result") }}/'+id, $.param(data), function(r) {
                    /*if (r.status == 1)
                        success(r.message);
                    else 
                        error(r.message);
*/
                    $('#table').DataTable().ajax.reload();
                    $(button).html('{{ __("publish result") }}');
                }); 
    }
    
    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/studentresearch2/data') }}?"+$.param(filter.filter)).load();
    }
    
    function downloadFile(button) {
        var html = $(button).html();
        //
        $(button).html("<i class='fa fa-spinner fa-spin' ></i>");
        $(button).attr('disabled', 'disabled');
        
        $.post('{{ url("/dashboard/studentresearch/download") }}', $.param(download.download), function(r){
            if (r.status == 1) {
                  window.open(r.data.url);
                success(r.message);
            } else {
                error(r.message);
            }
            
            $(button).html(html);
            $(button).removeAttr('disabled');
        });
    }
 
var download = new Vue({
    el: '#downloadModal',
    data: {   
        download: {
            _token: '{{ csrf_token() }}',
            level_id: null,
            department_id: null,
            course_id: null
        }
    },
    methods: { 
    },
    computed: {
    },
    watch: {
    }
});

      
var filter = new Vue({
    el: '#filter',
    data: { 
        filter: {
            course_id: null,
            research_id: null
        },  
    },
    methods: { 
    },
    computed: {
    },
    watch: {
    }
});



$(document).ready(function() {
    table =  $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10,
        "sorting": [0, 'DESC'],
        "ajax": "{{ url('/dashboard/studentresearch2/data') }}",
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
        "columns":[  
            { "data": "student_code" }, 
            { "data": "student_id" },
            { "data": "course_code" },  
            { "data": "course" }, 
            { "data": "research_id" }, 
            { "data": "file" }, 
            { "data": "result_id" }, 
            { "data": "upload_date" }, 
            { "data": "action" }
        ]
     });

     formAjax();
     
     $('.select2').select2();

});
</script>
@endsection
