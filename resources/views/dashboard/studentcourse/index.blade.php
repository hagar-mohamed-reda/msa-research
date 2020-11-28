@extends("dashboard.layout.app")

<link rel="stylesheet" href="{{ url('/') }}/css/uploader.css">
@section("title")
{{ __('student courses') }}
@endsection
@php
    $builder = (new App\StudentCourse)->getViewBuilder();
@endphp
@section('headers')

    <button class="w3-button w3-green" onclick="$('#importModal').modal()" >{{ __('import from excel') }}</button>

@endsection

@section("content")
<div class="filters w3-row" id="filter"  > 
        <div class="form-group has-feedback w3-col l2 m2 s2 w3-padding"> 
            <br>
            <button class="fa fa-search btn btn-success btn-flat" onclick="search()" ></button>
        </div> 
        <div class="form-group has-feedback w3-col l5 m5 s5 w3-padding">
            <label>{{ __("student") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.student_id=this.value"   >
                <option value="">{{ __('choose student') }}</option>
                @foreach(App\User::students()->get() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach 
            </select> 
        </div> 
        <div class="form-group has-feedback w3-col l5 m5 s5 w3-padding">
            <label>{{ __("course") }}</label>
            <select name="type" class="form-control select2"  onchange="filter.filter.course_id=this.value" >
                <option value="">{{ __('choose course') }}</option>
                @foreach(App\Course::all() as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach 
            </select> 
        </div> 
</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            @foreach($builder->cols as $col)
            <th>{{ $col['label'] }}</th>
            @endforeach
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional") 


<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('edit department') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" tabindex="-1" role="dialog" id="importModal" style="width: 100%!important;height: 100%!important" >

    <div class="modal-dialog modal-sm" role="document" >

        <div class="modal-content"   >

            <form action="{{ url('/dashboard/studentcourse/import') }}" enctype="multipart\form-data" class="form" method="post" id="import-form" >

                <div class="modal-header"  >

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <br>

                    <h4 class="modal-title text-center">{{ __('assign courses to students with excel') }}</h4>

                </div>

                <div class="modal-body"  >

                    {{ csrf_field() }}
                    <select name="type" class="form-control" onchange="this.value == 'edit'? $('.import-course-id').show(400) : $('.import-course-id').hide(400)" >
                        <option value="new" >{{ __('assign course to student') }}</option>
                        <option value="edit" >{{ __('assign course to student and remove old courses') }}</option>
                    </select> 
                    <br>
                    <select name="course_id" class="form-control select2 import-course-id" >
                        <option value="" >{{ __('select course') }}</option>
                        @foreach(App\Course::all() as $item)
                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <br>
                    <br>
                    <br>
                    <br>

                    <center class="center" > 

                    <div class="title text-capitalize">{{ __('Drop file to upload') }}</div>

                    <div class="dropzone">

                        <div class="content">

                            <img src="https://100dayscss.com/codepen/upload.svg" class="upload">

                            <span class="filename"></span>

                            <input type="file" class="input" name="courses" required="" >

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
<button class="fa fa-filter btn btn-primary btn-flat" onclick="$('.filters').slideToggle(300)" ></button>
@endsection

@section("js")
 
<script>

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

    
    showPage('dashboard/studentcourse');
}

formAjax(false, function(r){

    showDone();

});

    var table = null;



    $('.app-add-button').remove();
    function searchStudent(key) {
        if (key.length <= 0)
            return $(".student-list-item").show();
        
        $(".student-list-item").hide();
        $(".student-list-item").each(function(){
            if ($(this).text().indexOf(key) >= 0) {
                $(this).show();
            }
        });
    }
    function search() {  
        //alert();
        table.ajax.url("{{ url('/dashboard/studentcourse/data') }}?"+$.param(filter.filter)).load();
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
        "pageLength": 5,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
        "sorting": [0, 'DESC'],
        "ajax": "{{ url('/dashboard/studentcourse/data') }}",
        "columns":[
            @foreach($builder->cols as $col)
            { "data": "{{ $col['name'] }}" },
            @endforeach
            { "data": "action" }
        ]
     });

     formAjax();

});
</script>
@endsection
