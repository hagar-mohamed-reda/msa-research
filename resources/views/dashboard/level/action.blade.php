<div style="width: 120px" >

    @if (Auth::user()->_can('edit level'))
    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/level/edit') . '/' . $level->id }}')" ></i>
    @endif

    @if (Auth::user()->_can('remove level'))
    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/level/remove/') .'/' . $level->id }}')" ></i>
    @endif
</div>
