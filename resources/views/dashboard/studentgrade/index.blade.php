@extends("dashboard.layout.app")

<link rel="stylesheet" href="{{ url('/') }}/css/uploader.css">
@section("title")
{{ __('studentgrades') }}
@endsection
@php
    $builder = (new App\StudentGrade)->getViewBuilder();
@endphp

@section("content")
<div class="filters w3-row" id="filter"   > 
        
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
          
      
        <div class="form-group has-feedback w3-col l4 m4 s6 w3-padding">
            <label>{{ __("student") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.student_id=this.value"   >
                <option value="">{{ __('choose student') }}</option>
                @foreach(App\User::students()->get() as $item)
                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->code }}</option>
                @endforeach 
            </select> 
        </div>
          
        
        <div class="form-group has-feedback w3-col l4 m4 s6 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div> 
        
</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr> 
            <th>{{ __('code') }}</th> 
            <th>{{ __('set_number') }}</th> 
            <th>{{ __('student') }}</th> 
            <th>{{ __('course') }}</th> 
            <th>{{ __('grade') }}</th> 
            <th>{{ __('gpa') }}</th>   
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional") 
<!-- add modal -->
<div class="modal fade"  role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('add studentgrade') }}</center>
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
        <center class="modal-title w3-xlarge">{{ __('edit studentgrade') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" tabindex="-1" role="dialog" id="importModal" style="width: 100%!important;height: 100%!important" >

    <div class="modal-dialog modal-sm" role="document" >

        <div class="modal-content"   >

            <form action="{{ url('/dashboard/studentgrade/import') }}" enctype="multipart\form-data" class="form" method="post" id="import-form" >

                <div class="modal-header"  >

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <br>

                    <h4 class="modal-title text-center">{{ __('import grades from excel file') }}</h4>

                </div>

                <div class="modal-body"  >
                     

                    {{ csrf_field() }}



                    <center class="center" > 

                    <div class="title text-capitalize">{{ __('Drop file to upload') }}</div>

                    <div class="dropzone">

                        <div class="content">

                            <img src="https://100dayscss.com/codepen/upload.svg" class="upload">

                            <span class="filename"></span>

                            <input type="file" class="input" name="grades" required="" >

                        </div>

                    </div>

                    <img src="https://100dayscss.com/codepen/syncing.svg" class="syncing">

                    <img src="https://100dayscss.com/codepen/checkmark.svg" class="done"> 

                    <br>

                    <div class="bar"></div>

                    </center>

                    <br>

                    <br>

                </div> 

                <div class="modal-footer"   >

                    <div class="upload-btn text-capitalize"  

                         onclick="$('#import-form').submit()" >{{ __('upload file') }}</div>  

                </div>



            </form>

        </div><!-- /.modal-content -->

    </div><!-- /.modal-dialog --> 

</div><!-- /.modal --> 
  
@endsection

@section("headers")
 
    
<button class=" btn btn-primary btn-flat" onclick="$('.filters').slideToggle(300)" >
    <i class="fa fa-filter" ></i> {{ __('filters') }}
</button>
 
    <button class="w3-button w3-green" onclick="$('#importModal').modal()" >{{ __('import from excel') }}</button>
 
    <button class="w3-button w3-red" onclick="showPage('dashboard/studentgrade/coursenotmatch')" >{{ __('courses not match') }}</button>

@endsection

@section("js")
  
@if (!Auth::user()->_can('add studentgrade'))
<script>
    $('.app-add-button').remove();
</script>
@endif
<script>
    var table = null;
    
var droppedFiles = false;

var fileName = '';

var $dropzone = $('.dropzone');

var $button = $('.upload-btn');

var uploading = false;

var $syncing = $('.syncing');

var $done = $('.done');

var $bar = $('.bar');

var timeOut;



$dropzone.on('drag dragstart dragend dragover dragenter dragleave drop', function (e) {

    e.preventDefault();

    e.stopPropagation();

})

        .on('dragover dragenter', function () {

            $dropzone.addClass('is-dragover');

        })

        .on('dragleave dragend drop', function () {

            $dropzone.removeClass('is-dragover');

        })

        .on('drop', function (e) {

            droppedFiles = e.originalEvent.dataTransfer.files;

            fileName = droppedFiles[0]['name'];

            $("input:file")[0].files = droppedFiles;

            $('.filename').html(fileName);

            $('.dropzone .upload').hide();

        });



$button.bind('click', function () {

    startUpload();

});



$("input:file").change(function () {

    fileName = $(this)[0].files[0].name;

    $('.filename').html(fileName);

    $('.dropzone .upload').hide();

});



function startUpload() {

    if (!uploading && fileName != '') {

        uploading = true;

        $button.html('Uploading...');

        $dropzone.fadeOut();

        $syncing.addClass('active');

        $done.addClass('active');

        $bar.addClass('active');

        //timeoutID = window.setTimeout(showDone, 3200);

    }

}



function showDone() {

    $button.click(function(){

        $('#importModal').modal('hide');

    });

    $button.html('Done');
    
    showPage('dashboard/studentgrade');

}

formAjax(false, function(r){

    showDone();
    
});
     
    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/studentgrade/data') }}?"+$.param(filter.filter)).load();
    }
    
var filter = new Vue({
    el: '#filter',
    data: { 
        filter: {
            course_id: null,
            grade_id: null
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
        "ajax": "{{ url('/dashboard/studentgrade/data') }}",
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
        "columns":[  
            { "data": "code" }, 
            { "data": "set_number" }, 
            { "data": "student_id" }, 
            { "data": "course_id" }, 
            { "data": "grade" }, 
            { "data": "gpa" },  
            { "data": "action" }
        ]
     });

     formAjax();

});
</script>
@endsection
