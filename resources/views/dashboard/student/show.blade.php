<center>
    <h3 class="font" >
        {{ __('registered courses for student') }} {{ $student->name }}
    </h3>
</center>
<br>
<table class="table table-bordered" id="studentCourseTable" >
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('code') }}</th>
            <th>{{ __('name') }}</th>
            <th>{{ __('hour') }}</th>
            <th>{{ __('department') }}</th>
            <th>{{ __('notes') }}</th>
            <th>{{ __('times') }}</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach(App\StudentCourse::where('student_id', $student->id)->get() as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ optional($item->course)->code }}</td>
            <td>{{ optional($item->course)->name }}</td>
            <td>{{ optional($item->course)->hours }}</td>
            <td>{{ optional(optional($item->course)->department)->name }}</td>
            <td>{{ optional($item->course)->notes }}</td>
            <td>{{ $item->times <= 0? 1 : $item->times  }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
         $('#studentCourseTable').DataTable({  
            "paging": false,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],  
         });

         formAjax(); 
</script>



