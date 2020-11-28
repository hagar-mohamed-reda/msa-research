<div style="width: 120px" >

    @if (Auth::user()->_can('edit department'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/department/edit') . '/' . $department->id }}')" ></i>
    @endif

    @if (Auth::user()->_can('remove department'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/department/remove/') .'/' . $department->id }}')" ></i>
    @endif
</div>
