<div style="width: 120px" >

    @if (Auth::user()->_can('edit_studentgrade'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/studentgrade/edit') . '/' . $studentgrade->id }}')" ></i>
    @endif

    @if (Auth::user()->_can('remove_studentgrade'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/studentgrade/remove/') .'/' . $studentgrade->id }}')" ></i>
    @endif
</div>
