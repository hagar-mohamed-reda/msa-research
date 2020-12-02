@extends("dashboard.layout.app")

<link rel="stylesheet" href="{{ url('/') }}/css/uploader.css">
@section("title")
{{ __('students') }}
@endsection
@php
    $builder = (new App\Student)->getViewBuilder();
@endphp
@section('headers')

    <button class="w3-button w3-green" onclick="$('#importModal').modal()" >{{ __('import from excel') }}</button>

@endsection
@section("content")
<div>

</div>
<table class="table table-bordered" id="table" >
    <thead>
        <tr>
            @foreach($builder->cols as $col)
            @if ($col['name'] != 'password')
            <th>{{ $col['label'] }}</th>
            @endif
            @endforeach
            <th>{{ __('register courses') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

@endsection

@section("additional")
<!-- add modal
<div class="modal fade"  role="dialog" id="addModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('add student') }}</center>
      </div>
      <div class="modal-body">
        {!! $builder->loadAddView() !!}
      </div>
    </div>
  </div>
</div>
 -->

<!-- edit modal -->
<div class="modal fade"  role="dialog" id="editModal" style="width: 100%!important;height: 100%!important" >
    <div class="modal-dialog modal-" role="document" >
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <center class="modal-title w3-xlarge">{{ __('edit student') }}</center>
      </div>
      <div class="modal-body editModalPlace">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="importModal" style="width: 100%!important;height: 100%!important" >

    <div class="modal-dialog modal-sm" role="document" >

        <div class="modal-content"   >

            <form action="{{ url('/dashboard/student/import') }}" enctype="multipart\form-data" class="form" method="post" id="import-form" >

                <div class="modal-header"  >

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <br>

                    <h4 class="modal-title text-center">{{ __('import products from excel file') }}</h4>

                </div>

                <div class="modal-body"  >
                    <select class="form-control" name="type" >
                        <option value='new' >{{ __('add new users') }}</option>
                        <option value='edit' >{{ __('edit existing users') }}</option>
                        <option value='edit_national_id' >{{ __('edit national id of students') }}</option>
                        <option value='edit_graduated' >{{ __('edit graduated students') }}</option>
                    </select>

                    {{ csrf_field() }}



                    <center class="center" >

                    <div class="title text-capitalize">{{ __('Drop file to upload') }}</div>

                    <div class="dropzone">

                        <div class="content">

                            <img src="https://100dayscss.com/codepen/upload.svg" class="upload">

                            <span class="filename"></span>

                            <input type="file" class="input" name="users" required="" >

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

@section("js")

@if (!Auth::user()->_can('add student'))
<script>
    $('.app-add-button').remove();
</script>
@endif
<script>
    $('.app-add-button').click(function(){
        window.open('http://lms.seyouf.sphinxws.com/ar/dashboard/students/create');
    });


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

    showPage('dashboard/student');

}

formAjax(false, function(r){

    showDone();

});

$(document).ready(function() {
     $('#table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 5,
        "sorting": [0, 'DESC'],
        "ajax": "{{ url('/dashboard/student/data') }}",
        "columns":[
            @foreach($builder->cols as $col)
            @if ($col['name'] != 'password')
            { "data": "{{ $col['name'] }}" },
            @endif
            @endforeach
            { "data": "courses" },
            { "data": "action" }
        ]
     });

     formAjax();

});
</script>
@endsection
