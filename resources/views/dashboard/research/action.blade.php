@if (Auth::user()->type == 'doctor')
<div style="width: 120px" >

    <i class="fa fa-edit w3-text-orange w3-button" onclick="edit('{{ url('/dashboard/research/edit') . '/' . $research->id }}')" ></i>


    <i class="fa fa-trash w3-text-red w3-button" onclick="remove('', '{{ url('/dashboard/research/remove/') .'/' . $research->id }}')" ></i>
    
</div>
@endif
