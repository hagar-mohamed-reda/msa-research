<form method="post" class="form" action="{{ url('/dashboard/') }}/course/store" id="form">   
    <div class="box-body row">
        @csrf
        <div class="form-group w3-padding col-lg-12 col-md-12 col-sm-12">
            <label for="name">name</label>
            <input required="" type="text" class="form-control " id="name" name="name" value="" placeholder="name">
        </div>  
        <div class="form-group w3-padding col-lg-12 col-md-12 col-sm-12">
            <label for="code">code</label>
            <input required="" type="text" class="form-control " id="code" name="code" value="" placeholder="code">
        </div>  
        <div class="form-group w3-padding col-lg-12 col-md-12 col-sm-12">
            <label for="hours">hours</label>
            <input required="" type="number" class="form-control " id="hours" name="hours" value="" placeholder="hours">
        </div> 

        <div class="form-group w3-padding col-lg-12 col-md-12 col-sm-12">
            <label for="notes">notes</label>
            <input type="text" class="form-control " id="notes" name="notes" value="" placeholder="notes">
        </div> 

        <div>
            <table class="table table-bordered" >
                <tr>
                    <th>{{ __('department') }}</th>
                    <th></th>
                </tr>
                @foreach(App\Department::all() as $item)
                <tr>
                    <td>
                        {{ $item->name }} 
                        <br>
                        {{ optional($item->level)->name }}
                        <input type="hidden" name="department_id[]" value="{{ $item->id }}" >
                    </td>
                    <td>
                        <div class="material-switch pull-right w3-margin-top">
                            <input 
                                id="courseSwitch{{ $item->id }}"  
                                name="assign[]"  
                                onchange="this.checked ? this.value = 1 : this.value = 0"
                                type="checkbox"/>
                            <label for="courseSwitch{{ $item->id }}" class="label-primary"></label>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <br>
    <br>
    <div class="box-footer">
        <button type="button" class="btn bg-gray btn-flat margin shadow" data-dismiss="modal">اغلاق</button>
        <button type="submit" class="btn bg-purple btn-flat margin shadow">حفظ</button>
    </div>  
</form>   