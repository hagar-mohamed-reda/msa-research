<div style="width: 120px" >

    @if (Auth::user()->_can('edit student'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/student/edit') . '/' . $student->id }}')" ></i>
    @endif

    @if (Auth::user()->_can('remove student'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/student/remove/') .'/' . $student->id }}')" ></i>
    @endif
    
    <i class="fa fa-desktop w3-text-green w3-button"  onclick="edit('{{ url('/dashboard/student/show') . '/' . $student->id }}')" > {{ __('register courses') }} </i>
</div>
