<div style="width: 120px" >

    @if (Auth::user()->_can('edit admin'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/admin/edit') . '/' . $admin->id }}')" ></i>
    @endif

    @if (Auth::user()->_can('remove admin'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/admin/remove/') .'/' . $admin->id }}')" ></i>
    @endif
</div>
