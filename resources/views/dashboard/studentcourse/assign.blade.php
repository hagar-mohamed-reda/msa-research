 

<center class="modal-title w3-xlarge">{{ __('assign course to students') }}</center>
<form  class="form student-assign-course-form" method="post" action="{{ url('dashboard/studentcourse/assign/update') }}/{{ $course->id }}" >
    @csrf
    
    <table class="table table-bordered" id="tableOfStudentOfCourse" >
        <thead>
            <tr>
                <th>{{ __('code') }}</th>
                <th>{{ __('name') }}</th>
                <th>{{ __('level') }}</th>
                <th>{{ __('department') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    <center>
        <button class="btn btn-primary w3-block" >{{ __('save') }}</button>
    </center>
    
</form>

<script>
    $(document).ready(function() {
         table = $('#tableOfStudentOfCourse').DataTable({
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
            "ajax": "{{ url('/dashboard/student/course/data?course_id=') }}{{ $course->id }}') }}",
            "columns":[
                 
                { "data": "code" },
                { "data": "name" },
                { "data": "level" },
                { "data": "department" },
                { "data": "action" }
            ]
         });

         formAjax(); 

    }); 
</script>