<div style="width: 120px" >

    @if (Auth::user()->_can('edit doctor'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/doctor/edit') . '/' . $doctor->id }}')" ></i>
    @endif

    @if (Auth::user()->_can('remove doctor'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/doctor/remove/') .'/' . $doctor->id }}')" ></i>
    @endif
</div>
