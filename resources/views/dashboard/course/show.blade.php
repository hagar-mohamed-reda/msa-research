<center>
    <h1 class="font" >
        {{ __('register student on this course') }}
    </h1>
</center>
<br>
<table class="table table-bordered" id="studentCourseTable" >
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __('code') }}</th>
            <th>{{ __('name') }}</th>
            <th>{{ __('department') }}</th>
            <th>{{ __('level') }}</th>
            <th>{{ __('times') }}</th>
        </tr>
    </thead>
    
    <tbody>
        @foreach($studentCourses as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ optional($item->student)->code }}</td>
            <td>{{ optional($item->student)->name }}</td>
            <td>{{ optional(optional($item->student)->department)->name }}</td>
            <td>{{ optional(optional($item->student)->level)->name }}</td>
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



